<?php

namespace Tests\Unit;

use App\Services\PhilippineHolidayService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PhilippineHolidayServiceTest extends TestCase
{
    private PhilippineHolidayService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(PhilippineHolidayService::class);
    }

    public function test_christmas_day_2026_is_identified_as_holiday(): void
    {
        $isHoliday = $this->service->isHoliday('2026-12-25');
        $this->assertTrue($isHoliday, 'Christmas Day 2026 should be identified as a holiday.');

        $details = $this->service->getHolidayDetails('2026-12-25');
        $this->assertNotNull($details);
        $this->assertStringContainsString('Christmas', $details['name']);
    }

    public function test_normal_working_day_2026_is_allowed(): void
    {
        // 2026-10-14 is Wednesday and not a holiday
        $isHoliday = $this->service->isHoliday('2026-10-14');
        $this->assertFalse($isHoliday, '2026-10-14 should not be a holiday.');

        $check = $this->service->isAllowedBookingDate('2026-10-14');
        $this->assertTrue($check['allowed'], '2026-10-14 should be an allowed booking date.');
        $this->assertNull($check['reason']);
    }

    public function test_new_year_day_2027_is_identified_as_holiday_dynamic_year(): void
    {
        // Tests multi-year support dynamically
        $isHoliday = $this->service->isHoliday('2027-01-01');
        $this->assertTrue($isHoliday, 'New Year Day 2027 should be identified as a holiday.');
    }

    public function test_weekends_are_rejected(): void
    {
        // 2026-10-17 is Saturday, 2026-10-18 is Sunday
        $satCheck = $this->service->isAllowedBookingDate('2026-10-17');
        $this->assertFalse($satCheck['allowed'], 'Saturday booking should be rejected.');
        $this->assertStringContainsString('weekdays', $satCheck['reason']);

        $sunCheck = $this->service->isAllowedBookingDate('2026-10-18');
        $this->assertFalse($sunCheck['allowed'], 'Sunday booking should be rejected.');
        $this->assertStringContainsString('weekdays', $sunCheck['reason']);
    }

    public function test_holiday_booking_date_is_rejected(): void
    {
        $check = $this->service->isAllowedBookingDate('2026-12-25');
        $this->assertFalse($check['allowed'], 'Christmas Day booking should be rejected.');
        $this->assertStringContainsString('Christmas Day', $check['reason']);
    }

    public function test_holidays_are_cached_by_year(): void
    {
        $this->service->getHolidaysForYear(2026);
        $this->assertTrue(Cache::has('philippine_holidays_2026'), 'Holidays should be cached in Cache with key philippine_holidays_2026');
    }

    public function test_future_month_november_3_2026_is_allowed(): void
    {
        // 2026-11-03 is a Tuesday and not a holiday
        $isHoliday = $this->service->isHoliday('2026-11-03');
        $this->assertFalse($isHoliday, 'November 3, 2026 should not be a holiday.');

        $check = $this->service->isAllowedBookingDate('2026-11-03');
        $this->assertTrue($check['allowed'], 'November 3, 2026 should be an allowed booking date.');
        $this->assertNull($check['reason']);
    }

    public function test_past_date_is_rejected(): void
    {
        // 2025-01-15 is in the past
        $check = $this->service->isAllowedBookingDate('2025-01-15');
        $this->assertFalse($check['allowed'], 'Past dates must be rejected.');
        $this->assertStringContainsString('future dates', strtolower($check['reason']));
    }

    public function test_fail_safe_prevents_booking_when_api_fails_and_no_cache(): void
    {
        Cache::forget('philippine_holidays_2099');

        Http::fake([
            'https://calendarific.com/*' => Http::response('Server Error', 500),
        ]);

        $check = $this->service->isAllowedBookingDate('2099-05-15');
        $this->assertFalse($check['allowed'], 'Booking should fail safe and be prevented when API is down.');
        $this->assertStringContainsString('unavailable', $check['reason']);
    }
}
