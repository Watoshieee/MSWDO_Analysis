<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PhilippineHolidayService
{
    private const TIMEZONE = 'Asia/Manila';
    private const CACHE_PREFIX = 'philippine_holidays_';
    private const CACHE_TTL_DAYS = 30;

    /**
     * Determine if a date is a Philippine national / non-working holiday.
     *
     * @param string|\DateTimeInterface $date
     * @return bool
     * @throws \RuntimeException If API is unavailable and no cached data is present
     */
    public function isHoliday(string|\DateTimeInterface $date): bool
    {
        $details = $this->getHolidayDetails($date);
        return $details !== null;
    }

    /**
     * Get holiday details for a given date if it is an official Philippine holiday.
     *
     * @param string|\DateTimeInterface $date
     * @return array|null Returns ['name' => ..., 'primary_type' => ..., 'type' => ...] or null
     * @throws \RuntimeException If API is unavailable and no cached data is present
     */
    public function getHolidayDetails(string|\DateTimeInterface $date): ?array
    {
        $carbon = $this->parseToManilaCarbon($date);
        $dateKey = $carbon->format('Y-m-d');
        $year = (int) $carbon->format('Y');

        $holidays = $this->getHolidaysForYear($year);

        return $holidays[$dateKey] ?? null;
    }

    /**
     * Comprehensive booking date check for MSWDO appointments:
     * - Checks if weekend (Saturday or Sunday)
     * - Checks if Philippine national / non-working holiday
     * - Handles fail-safe if holiday service cannot verify date
     *
     * @param string|\DateTimeInterface $date
     * @return array ['allowed' => bool, 'reason' => ?string, 'holiday' => ?array]
     */
    public function isAllowedBookingDate(string|\DateTimeInterface $date): array
    {
        try {
            $carbon = $this->parseToManilaCarbon($date);
        } catch (\Exception $e) {
            return [
                'allowed' => false,
                'reason'  => 'Invalid appointment date format.',
                'holiday' => null,
            ];
        }

        // 1. Check Past Dates or Same Day (Must be strictly in the future, starting tomorrow)
        $today = Carbon::today(self::TIMEZONE);
        if ($carbon->lessThanOrEqualTo($today)) {
            return [
                'allowed' => false,
                'reason'  => 'Appointments can only be booked for future dates (starting tomorrow). Past dates and same-day bookings are not allowed.',
                'holiday' => null,
            ];
        }

        // 2. Check Weekend (Saturday / Sunday)
        if ($carbon->isWeekend()) {
            return [
                'allowed' => false,
                'reason'  => 'Appointments can only be booked on weekdays (Mon–Fri).',
                'holiday' => null,
            ];
        }

        // 3. Check Philippine Holiday
        try {
            $holiday = $this->getHolidayDetails($carbon);
            if ($holiday !== null) {
                $holidayName = $holiday['name'] ?? 'Philippine Holiday';
                return [
                    'allowed' => false,
                    'reason'  => "Appointments cannot be booked on Philippine holidays ({$holidayName}). Please select another date.",
                    'holiday' => $holiday,
                ];
            }
        } catch (\Throwable $e) {
            Log::error('PhilippineHolidayService: Failed to verify holiday for booking', [
                'date'  => $carbon->format('Y-m-d'),
                'error' => $e->getMessage(),
            ]);

            // Fail-safe: do NOT allow booking to bypass when status cannot be confirmed
            return [
                'allowed' => false,
                'reason'  => 'Holiday verification is temporarily unavailable. Please try again in a moment.',
                'holiday' => null,
            ];
        }

        return [
            'allowed' => true,
            'reason'  => null,
            'holiday' => null,
        ];
    }

    /**
     * Retrieve all Philippine holidays for a specific year, leveraging Laravel Cache.
     *
     * @param int $year
     * @return array Keyed by 'Y-m-d'
     * @throws \RuntimeException
     */
    public function getHolidaysForYear(int $year): array
    {
        $cacheKey = self::CACHE_PREFIX . $year;

        // Return cached holidays if available
        if (Cache::has($cacheKey)) {
            $cached = Cache::get($cacheKey);
            if (is_array($cached)) {
                return $cached;
            }
        }

        // Fetch from Nager.Date API
        $holidays = $this->fetchHolidaysFromApi($year);

        // Cache the processed holidays
        Cache::put($cacheKey, $holidays, now()->addDays(self::CACHE_TTL_DAYS));

        return $holidays;
    }

    /**
     * Clear cached holidays for a specific year (or default current year).
     */
    public function clearCache(?int $year = null): void
    {
        $year = $year ?? (int) now(self::TIMEZONE)->format('Y');
        Cache::forget(self::CACHE_PREFIX . $year);
    }

    /**
     * Fetch and filter holidays from Nager.Date API for the specified year.
     *
     * @param int $year
     * @return array Keyed by 'Y-m-d'
     * @throws \RuntimeException
     */
    protected function fetchHolidaysFromApi(int $year): array
    {
        $url = "https://date.nager.at/api/v3/PublicHolidays/{$year}/PH";

        try {
            $response = Http::timeout(10)->acceptJson()->get($url);
        } catch (\Throwable $e) {
            Log::error('PhilippineHolidayService: Network timeout or error calling Nager.Date', [
                'year'  => $year,
                'error' => $e->getMessage(),
            ]);
            throw new \RuntimeException('Could not connect to holiday verification service.', 0, $e);
        }

        if (!$response->successful()) {
            Log::error('PhilippineHolidayService: Nager.Date returned non-200 HTTP code', [
                'status' => $response->status(),
                'year'   => $year,
                'body'   => $response->body(),
            ]);
            throw new \RuntimeException('Holiday verification service returned an error status.');
        }

        $rawHolidays = $response->json();
        if (!is_array($rawHolidays)) {
            Log::error('PhilippineHolidayService: Nager.Date returned non-array JSON response', [
                'year' => $year,
                'body' => $response->body(),
            ]);
            throw new \RuntimeException('Holiday verification service returned an invalid response structure.');
        }

        return $this->processAndFilterHolidays($rawHolidays, $year);
    }

    /**
     * Process and filter official Philippine public/national holidays from Nager.Date:
     *
     * @param array $rawHolidays
     * @param int $year
     * @return array Keyed by 'Y-m-d'
     */
    protected function processAndFilterHolidays(array $rawHolidays, int $year): array
    {
        $filtered = [];

        foreach ($rawHolidays as $holiday) {
            $dateKey = $holiday['date'] ?? null;
            if (!$dateKey || !is_string($dateKey)) {
                continue;
            }

            $dateKey = substr(trim($dateKey), 0, 10);
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateKey)) {
                continue;
            }

            $types = (array) ($holiday['types'] ?? ['Public']);
            $primaryType = $types[0] ?? 'Public';

            // Filter out purely observational days if any
            $typesLower = array_map('strtolower', $types);
            if (in_array('observance', $typesLower, true) && !in_array('public', $typesLower, true)) {
                continue;
            }

            $filtered[$dateKey] = [
                'name'         => $holiday['name'] ?? 'Philippine Holiday',
                'local_name'   => $holiday['localName'] ?? null,
                'primary_type' => $primaryType,
                'type'         => $types,
                'date'         => $dateKey,
            ];
        }

        return $filtered;
    }

    /**
     * Safely parse input date to Carbon instance configured with Asia/Manila timezone.
     *
     * @param string|\DateTimeInterface $date
     * @return Carbon
     */
    private function parseToManilaCarbon(string|\DateTimeInterface $date): Carbon
    {
        if ($date instanceof Carbon) {
            return $date->copy()->setTimezone(self::TIMEZONE);
        }

        if ($date instanceof \DateTimeInterface) {
            return Carbon::instance($date)->setTimezone(self::TIMEZONE);
        }

        // Date string (e.g. '2026-12-25')
        return Carbon::parse($date, self::TIMEZONE);
    }
}
