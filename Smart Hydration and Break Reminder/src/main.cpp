/* Arduino example code for DHT11, DHT22/AM2302 
  and DHT21/AM2301 temperature and humidity sensors 
  with I2C LCD. More info: www.www.makerguides.com */

#include "DHT.h"
#include "Arduino.h"
#include "Wire.h"
#include "hd44780.h"
#include "hd44780ioClass/hd44780_I2Cexp.h"

// Set DHT pin:
#define DHTPIN 2

// Set DHT type, uncomment whatever type you're using!
//#define DHTTYPE DHT11   // DHT 11 
#define DHTTYPE DHT22   // DHT 22  (AM2302)
//#define DHTTYPE DHT21   // DHT 21 (AM2301)

// Initialize DHT sensor for normal 16mhz Arduino:
DHT dht = DHT(DHTPIN, DHTTYPE);

hd44780_I2Cexp lcd;
bool lcdReady = false;

void printI2CDevices() {
  bool foundAny = false;
  Serial.println("I2C scan start...");
  for (uint8_t address = 1; address < 127; address++) {
    Wire.beginTransmission(address);
    if (Wire.endTransmission() == 0) {
      foundAny = true;
      Serial.print("I2C device at 0x");
      if (address < 16) {
        Serial.print('0');
      }
      Serial.println(address, HEX);
    }
  }
  if (!foundAny) {
    Serial.println("No I2C devices found.");
  }
}

void setup() {
  Serial.begin(115200);
  delay(500);

  Wire.begin();
  printI2CDevices();

  // Setup sensor:
  dht.begin();

  int lcdStatus = lcd.begin(16, 2); // Use 20,4 for 2004 LCD
  if (lcdStatus == 0) {
    lcdReady = true;
    lcd.backlight();
    lcd.setCursor(0, 0);
    lcd.print("LCD OK");
    Serial.println("LCD initialized (hd44780_I2Cexp)");
    delay(1200);
    lcd.clear();
  } else {
    Serial.print("ERROR: LCD init failed, code=");
    Serial.println(lcdStatus);
  }
}

void loop() {
  // Wait a few seconds between measurements:
  delay(2000);

  // Reading temperature or humidity takes about 250 milliseconds!
  // Sensor readings may also be up to 2 seconds 'old' (its a very slow sensor)

  // Read the humidity in %:
  float h = dht.readHumidity();
  // Read the temperature as Celsius:
  float t = dht.readTemperature();
  // Read the temperature as Fahrenheit:
  float f = dht.readTemperature(true);

  // Compute heat index in Fahrenheit (default):
  float hif = dht.computeHeatIndex(f, h);
  // Compute heat index in Celsius:
  float hic = dht.computeHeatIndex(t, h, false);

  (void)hif;
  (void)hic;

  if (!lcdReady) {
    Serial.println("ERROR: LCD not ready. Check wiring/power/contrast.");
    return;
  }

  if (isnan(h) || isnan(t) || isnan(f)) {
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("DHT read failed");
    lcd.setCursor(0, 1);
    lcd.print("Check wiring");
    Serial.println("ERROR: DHT read failed. Check pin, sensor type, and pull-up.");
    return;
  }

  // Print the temperature and the humidity on the LCD:
  lcd.setCursor(0, 0);
  lcd.print("Temp:      ");
  lcd.setCursor(6, 0);
  lcd.print(t, 1);
  lcd.print(" " "\xDF" "C   ");

  lcd.setCursor(0, 1);
  lcd.print("Humid:     ");
  lcd.setCursor(7, 1);
  lcd.print(h, 1);
  lcd.print(" %   ");

  Serial.print("Temp: ");
  Serial.print(t, 1);
  Serial.print(" C, Humid: ");
  Serial.print(h, 1);
  Serial.println(" %");

  // Machine-friendly output for the laptop bridge script.
  Serial.print("SENSOR,");
  Serial.print(t, 1);
  Serial.print(",");
  Serial.println(h, 1);
}