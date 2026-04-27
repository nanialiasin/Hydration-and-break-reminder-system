#!/usr/bin/env python3
"""Read sensor values from Arduino serial and push to Laravel /sensor/ingest.

Expected serial formats:
- Temp: 26.4 C, Humid: 58.1 %
- SENSOR,26.4,58.1
"""

from __future__ import annotations

import argparse
import json
import re
import sys
import time
import urllib.error
import urllib.parse
import urllib.request


TEXT_PATTERN = re.compile(
    r"Temp:\s*(-?\d+(?:\.\d+)?)\s*C,\s*Humid:\s*(-?\d+(?:\.\d+)?)\s*%",
    re.IGNORECASE,
)
CSV_PATTERN = re.compile(r"^SENSOR,(-?\d+(?:\.\d+)?),(-?\d+(?:\.\d+)?)$", re.IGNORECASE)


def parse_line(line: str) -> tuple[float, float] | None:
    line = line.strip()
    if not line:
        return None

    text_match = TEXT_PATTERN.search(line)
    if text_match:
        return float(text_match.group(1)), float(text_match.group(2))

    csv_match = CSV_PATTERN.search(line)
    if csv_match:
        return float(csv_match.group(1)), float(csv_match.group(2))

    return None


def build_url(base_url: str) -> str:
    return base_url.rstrip("/") + "/sensor/ingest"


def send_reading(url: str, temperature: float, humidity: float, key: str | None, timeout: float) -> tuple[bool, str]:
    params = {
        "temperature": f"{temperature:.1f}",
        "humidity": f"{humidity:.1f}",
    }
    if key:
        params["key"] = key

    query = urllib.parse.urlencode(params)
    ingest_url = f"{url}?{query}"

    request = urllib.request.Request(ingest_url, method="GET")

    try:
        with urllib.request.urlopen(request, timeout=timeout) as response:
            payload = response.read().decode("utf-8", errors="replace")
            if 200 <= response.status < 300:
                return True, payload
            return False, f"HTTP {response.status}: {payload}"
    except urllib.error.HTTPError as exc:
        details = exc.read().decode("utf-8", errors="replace")
        return False, f"HTTP {exc.code}: {details}"
    except urllib.error.URLError as exc:
        if "getaddrinfo failed" in str(exc):
            return False, f"Network error: {exc}. Is the app running and the URL correct?"
        return False, f"Network error: {exc}"


def main() -> int:
    parser = argparse.ArgumentParser(description="Bridge Arduino serial readings to Laravel sensor ingest endpoint.")
    parser.add_argument("--port", required=True, help="Serial port, e.g. COM4")
    parser.add_argument("--baud", type=int, default=115200, help="Baud rate (default: 115200)")
    parser.add_argument("--app-url", default="http://hydrapulse.test", help="Laravel app base URL")
    parser.add_argument("--key", default=None, help="Optional HYDRATION_SENSOR_KEY")
    parser.add_argument("--timeout", type=float, default=5.0, help="HTTP timeout seconds")
    parser.add_argument("--dedupe-seconds", type=float, default=1.5, help="Minimum interval between uploads")
    args = parser.parse_args()

    try:
        import serial  # type: ignore
    except Exception:
        print("ERROR: pyserial is required. Install with: pip install pyserial", file=sys.stderr)
        return 2

    ingest_url = build_url(args.app_url)

    print(f"Posting to {ingest_url}")

    last_sent_at = 0.0
    last_values: tuple[float, float] | None = None

    ser = None

    while True:
        try:
            if ser is None or not ser.is_open:
                print(f"Connecting serial port {args.port} @ {args.baud}...")
                ser = serial.Serial(args.port, args.baud, timeout=1)
                print("Serial connected.")

            raw = ser.readline()
            line = raw.decode("utf-8", errors="ignore").strip()
            if not line:
                continue

            values = parse_line(line)
            if values is None:
                continue

            temperature, humidity = values
            now = time.time()

            if (
                last_values is not None
                and (temperature, humidity) == last_values
                and (now - last_sent_at) < args.dedupe_seconds
            ):
                continue

            ok, response_text = send_reading(
                ingest_url,
                temperature,
                humidity,
                key=args.key,
                timeout=args.timeout,
            )

            if ok:
                last_values = (temperature, humidity)
                last_sent_at = now
                print(f"OK temp={temperature:.1f}C hum={humidity:.1f}%")
            else:
                print(f"ERROR temp={temperature:.1f} hum={humidity:.1f}: {response_text}")

                # Print helpful details for JSON errors.
                try:
                    parsed = json.loads(response_text)
                    print(parsed)
                except Exception:
                    pass

        except KeyboardInterrupt:
            print("Stopped by user.")
            if ser and ser.is_open:
                ser.close()
            return 0
        except serial.SerialException as exc:
            print(f"WARN: serial disconnected or unavailable: {exc}")
            if ser and ser.is_open:
                ser.close()
            ser = None
            time.sleep(1.0)
        except Exception as exc:
            print(f"WARN: bridge loop error: {exc}")
            time.sleep(0.5)


if __name__ == "__main__":
    raise SystemExit(main())
