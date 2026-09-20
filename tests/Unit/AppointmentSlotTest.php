<?php

namespace Tests\Unit;

use App\Models\Appointment;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AppointmentSlotTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('appointments')) {
            Schema::create('appointments', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->unsigned()->index();
                $table->string('municipality');
                $table->date('appointment_date');
                $table->string('appointment_time');
                $table->string('interview_type')->default('face_to_face');
                $table->string('program_type')->default('Solo_Parent');
                $table->string('status')->default('pending');
                $table->text('user_notes')->nullable();
                $table->text('admin_notes')->nullable();
                $table->string('reschedule_date')->nullable();
                $table->string('reschedule_time')->nullable();
                $table->text('reschedule_reason')->nullable();
                $table->string('reschedule_status')->nullable();
                $table->timestamp('reschedule_requested_at')->nullable();
                $table->timestamp('reminded_at')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    protected function tearDown(): void
    {
        Appointment::truncate();
        parent::tearDown();
    }

    public function test_max_per_slot_is_one(): void
    {
        $this->assertSame(1, Appointment::maxPerSlot(), 'Each appointment slot should allow exactly 1 booking.');
    }

    public function test_slot_count_increases_when_booked(): void
    {
        $testDate = '2027-05-18';
        $testTime = '09:00';
        $municipality = 'Liliw';

        $initialCount = Appointment::slotCount($testDate, $testTime, $municipality);
        $this->assertSame(0, $initialCount, 'Initial slot count should be 0.');

        // Create a test appointment
        Appointment::create([
            'user_id'          => 1,
            'municipality'     => $municipality,
            'appointment_date' => $testDate,
            'appointment_time' => $testTime,
            'interview_type'   => 'face_to_face',
            'program_type'     => 'Solo_Parent',
            'status'           => 'pending',
        ]);

        $newCount = Appointment::slotCount($testDate, $testTime, $municipality);
        $this->assertSame(1, $newCount, 'Slot count must increase to 1 after booking.');

        // Verify slotsForDate marks it full
        $slots = Appointment::slotsForDate($testDate, $municipality);
        $targetSlot = null;
        foreach ($slots as $slot) {
            if ($slot['time'] === $testTime) {
                $targetSlot = $slot;
                break;
            }
        }

        $this->assertNotNull($targetSlot, 'Slot 09:00 should exist in slotsForDate.');
        $this->assertSame(1, $targetSlot['taken'], 'Slot taken count should be 1.');
        $this->assertSame(0, $targetSlot['remaining'], 'Slot remaining count should be 0.');
        $this->assertTrue($targetSlot['full'], 'Slot should be marked full.');
    }

    public function test_active_statuses_count_towards_slot_while_cancelled_does_not(): void
    {
        $testDate = '2027-05-19';
        $testTime = '10:00';
        $municipality = 'Majayjay';

        $appt = Appointment::create([
            'user_id'          => 1,
            'municipality'     => $municipality,
            'appointment_date' => $testDate,
            'appointment_time' => $testTime,
            'interview_type'   => 'face_to_face',
            'program_type'     => 'AICS_Medical',
            'status'           => 'pending',
        ]);

        $this->assertSame(1, Appointment::slotCount($testDate, $testTime, $municipality));

        // Confirmed status
        $appt->update(['status' => 'confirmed']);
        $this->assertSame(1, Appointment::slotCount($testDate, $testTime, $municipality));

        // Validated status
        $appt->update(['status' => 'validated']);
        $this->assertSame(1, Appointment::slotCount($testDate, $testTime, $municipality));

        // Cancelled status frees the slot
        $appt->update(['status' => 'cancelled']);
        $this->assertSame(0, Appointment::slotCount($testDate, $testTime, $municipality), 'Cancelled appointments should not count towards the slot.');

        // Rejected status also frees the slot
        $appt->update(['status' => 'rejected']);
        $this->assertSame(0, Appointment::slotCount($testDate, $testTime, $municipality), 'Rejected appointments should not count towards the slot.');
    }

    public function test_slot_count_handles_time_with_seconds(): void
    {
        $testDate = '2027-05-20';
        $testTime = '11:15:00';
        $queryTime = '11:15';
        $municipality = 'Nagcarlan';

        Appointment::create([
            'user_id'          => 1,
            'municipality'     => $municipality,
            'appointment_date' => $testDate,
            'appointment_time' => $testTime,
            'interview_type'   => 'face_to_face',
            'program_type'     => 'Solo_Parent',
            'status'           => 'pending',
        ]);

        $this->assertSame(1, Appointment::slotCount($testDate, $queryTime, $municipality));

        $slots = Appointment::slotsForDate($testDate, $municipality);
        $targetSlot = null;
        foreach ($slots as $slot) {
            if ($slot['time'] === $queryTime) {
                $targetSlot = $slot;
                break;
            }
        }

        $this->assertNotNull($targetSlot);
        $this->assertSame(1, $targetSlot['taken']);
        $this->assertTrue($targetSlot['full']);
    }

    public function test_subsequent_booking_on_same_date_and_time_is_blocked(): void
    {
        $testDate = '2027-06-10';
        $testTime = '14:30';
        $municipality = 'Liliw';

        // 1. Initial check: slot is open
        $this->assertLessThan(Appointment::maxPerSlot(), Appointment::slotCount($testDate, $testTime, $municipality));

        // 2. User 1 books the slot
        Appointment::create([
            'user_id'          => 1,
            'municipality'     => $municipality,
            'appointment_date' => $testDate,
            'appointment_time' => $testTime,
            'interview_type'   => 'face_to_face',
            'program_type'     => 'Solo_Parent',
            'status'           => 'pending',
        ]);

        // 3. User 2 checks if slot is bookable: slot is full
        $isFull = Appointment::slotCount($testDate, $testTime, $municipality) >= Appointment::maxPerSlot();
        $this->assertTrue($isFull, 'Slot must be full so user 2 cannot book the same date and time.');

        // 4. Checking slotsForDate shows remaining = 0 and full = true
        $slots = Appointment::slotsForDate($testDate, $municipality);
        $slot1430 = collect($slots)->firstWhere('time', $testTime);
        $this->assertNotNull($slot1430);
        $this->assertSame(0, $slot1430['remaining']);
        $this->assertTrue($slot1430['full']);

        // 5. Another municipality (e.g., Majayjay) is not blocked on the same date and time
        $otherMuniFull = Appointment::slotCount($testDate, $testTime, 'Majayjay') >= Appointment::maxPerSlot();
        $this->assertFalse($otherMuniFull, 'Other municipalities should have their own independent slot schedule.');
    }
}
