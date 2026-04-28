<?php

namespace App\Services;

use Carbon\Carbon;

class HydrationReminderService
{
    public const WEIGHT_CLASS_LIGHT = 'Lightweight';
    public const WEIGHT_CLASS_MIDDLE = 'Middleweight';
    public const WEIGHT_CLASS_HEAVY = 'Heavyweight';

    // Calculate a baseline interval from environment and duration.
    public function calculateInterval($temperature = 25, $humidity = 50, $durationMinutes = 30): int
    {
        $interval = 30;

        // Heat
        if ($temperature >= 35)      $interval -= 10;
        elseif ($temperature >= 30)  $interval -= 7;
        elseif ($temperature >= 27)  $interval -= 3;

        // Humidity
        if ($humidity >= 80)         $interval -= 5;
        elseif ($humidity >= 60)     $interval -= 2;

        // Intensity/Duration
        if ($durationMinutes >= 90)  $interval -= 5;

        return max($interval, 10);
    }

    // Calculate how much water is needed for a healthy daily target based on body weight.
    // This uses a broadly accepted medical guideline of about 30-40 ml per kg.
    public function calculateDailyHydrationTarget(int $weightKg): int
    {
        $targetMl = (int) round($weightKg * 35);

        return max(1500, min($targetMl, 3000));
    }

    // Calculate a small adjustment to the daily target for heat, humidity, or long activity.
    public function calculateAdjustedDailyHydrationTarget(int $weightKg, $temperature = 25, $humidity = 50, $durationMinutes = 30): int
    {
        $baseTargetMl = $this->calculateDailyHydrationTarget($weightKg);
        $multiplier = 1.0;

        if ($temperature >= 32) {
            $multiplier += 0.10;
        } elseif ($temperature >= 30) {
            $multiplier += 0.05;
        }

        if ($humidity >= 80) {
            $multiplier += 0.10;
        } elseif ($humidity >= 70) {
            $multiplier += 0.05;
        }

        if ($durationMinutes >= 90) {
            $multiplier += 0.10;
        } elseif ($durationMinutes >= 60) {
            $multiplier += 0.05;
        }

        // Weight class adjustment keeps targets distinct across athlete classes.
        $multiplier *= $this->getWeightClassHydrationFactor($weightKg);

        $adjustedTargetMl = (int) round($baseTargetMl * $multiplier);

        return max(1500, min($adjustedTargetMl, 3000));
    }

    public function getWeightClass(?float $weightKg): ?string
    {
        if (!$weightKg || $weightKg <= 0) {
            return null;
        }

        if ($weightKg < 60) {
            return self::WEIGHT_CLASS_LIGHT;
        }

        if ($weightKg < 80) {
            return self::WEIGHT_CLASS_MIDDLE;
        }

        return self::WEIGHT_CLASS_HEAVY;
    }

    public function getWeightClassHydrationFactor(?float $weightKg): float
    {
        return match ($this->getWeightClass($weightKg)) {
            self::WEIGHT_CLASS_LIGHT => 0.95,
            self::WEIGHT_CLASS_HEAVY => 1.08,
            default => 1.00,
        };
    }

    // Return a practical default sip size when the athlete has not calibrated one yet.
    public function calculateDefaultSipSize(?int $weightKg = null): int
    {
        if (!$weightKg || $weightKg <= 0) {
            return 20;
        }

        if ($weightKg < 60) {
            return 20;
        }

        if ($weightKg < 80) {
            return 22;
        }

        return 25;
    }

    // Keep any sip measurement within the app's intended 20-25 ml range.
    public function normalizeSipSize(?float $sipMl, ?int $weightKg = null): int
    {
        if (is_numeric($sipMl) && (float) $sipMl > 0) {
            return max(20, min(25, (int) round((float) $sipMl)));
        }

        return $this->calculateDefaultSipSize($weightKg);
    }

    // Estimate a sensible per-reminder drink amount based on daily target.
    public function calculateReminderVolume(int $weightKg, $temperature = 25, $humidity = 50, $durationMinutes = 30): int
    {
        $targetMl = $this->calculateAdjustedDailyHydrationTarget($weightKg, $temperature, $humidity, $durationMinutes);
        $suggestedMl = (int) round($targetMl / 18);

        return max(150, min($suggestedMl, 250));
    }

    // Return a practical daily range around the adjusted target.
    public function calculateDailyHydrationRange(int $weightKg, $temperature = 25, $humidity = 50, $durationMinutes = 30): array
    {
        $targetMl = $this->calculateAdjustedDailyHydrationTarget($weightKg, $temperature, $humidity, $durationMinutes);
        $minMl = (int) round($targetMl * 0.85);
        $maxMl = (int) round($targetMl * 1.15);

        return [
            'target' => $targetMl,
            'min' => max(1200, $minMl),
            'max' => max($minMl, $maxMl),
        ];
    }

    // Apply environmental and weight-based adjustments to determine a reminder interval.
    public function calculateAdjustedInterval(int $baseInterval, $temperature = 25, $humidity = 50, $durationMinutes = 30, ?int $weightKg = null): int
    {
        $environmentInterval = $this->calculateInterval($temperature, $humidity, $durationMinutes);

        if ($weightKg && $weightKg > 0) {
            $dailyTargetMl = $this->calculateAdjustedDailyHydrationTarget($weightKg, $temperature, $humidity, $durationMinutes);
            $drinkMl = $this->calculateReminderVolume($weightKg, $temperature, $humidity, $durationMinutes);
            $remindersNeeded = max(1, (int) ceil($dailyTargetMl / $drinkMl));
            $trackingWindow = max($durationMinutes, 720);
            $targetInterval = max(10, (int) floor($trackingWindow / $remindersNeeded));

            return min($environmentInterval, $targetInterval);
        }

        $deltaFromDefault = $environmentInterval - 30;
        return max($baseInterval + $deltaFromDefault, 5);
    }

    public function calculateNextReminder($startTime, int $intervalMinutes): Carbon
    {
        return Carbon::parse($startTime)->addMinutes($intervalMinutes);
    }

    public function shouldRemind($lastReminderTime, int $intervalMinutes): bool
    {
        $nextTime = Carbon::parse($lastReminderTime)->addMinutes($intervalMinutes);
        return now()->greaterThanOrEqualTo($nextTime);
    }
}