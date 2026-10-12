<?php

namespace Tests\Unit;

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Api\AicsApiController;
use App\Http\Controllers\Api\SoloParentApiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ControllerHolidayValidationTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = new User();
        $this->user->id = 1;
        $this->user->full_name = 'Juan Dela Cruz';
        $this->user->email = 'juan@example.com';
        $this->user->municipality = 'Majayjay';
        $this->user->role = 'user';
        $this->user->exists = true;

        Auth::setUser($this->user);
    }

    public function test_web_appointment_slots_rejects_christmas_day(): void
    {
        $controller = app(AppointmentController::class);
        $request = Request::create('/user/appointments/slots', 'GET', ['date' => '2026-12-25']);

        $response = $controller->getAvailableSlots($request);

        $this->assertEquals(422, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $data);
        $this->assertStringContainsString('Christmas Day', $data['error']);
    }

    public function test_web_appointment_slots_rejects_weekend(): void
    {
        $controller = app(AppointmentController::class);
        // 2026-10-17 is Saturday
        $request = Request::create('/user/appointments/slots', 'GET', ['date' => '2026-10-17']);

        $response = $controller->getAvailableSlots($request);

        $this->assertEquals(422, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $data);
        $this->assertStringContainsString('weekend', strtolower($data['error']));
    }

    public function test_web_appointment_store_rejects_holiday_date(): void
    {
        $controller = app(AppointmentController::class);
        $request = Request::create('/user/appointments', 'POST', [
            'appointment_date' => '2026-12-25',
            'appointment_time' => '09:00',
            'interview_type'   => 'face_to_face',
            'program_type'     => 'Solo_Parent',
        ]);
        $request->setLaravelSession(app('session.store'));

        $response = $controller->store($request);

        $this->assertTrue($response->isRedirect());
        $this->assertStringContainsString('Christmas Day', session('appt_error'));
    }

    public function test_aics_api_slots_rejects_holiday_date(): void
    {
        $controller = app(AicsApiController::class);
        $request = Request::create('/mobile-api/aics/medical/slots', 'GET', ['date' => '2026-12-25']);

        $response = $controller->getAvailableSlots($request);

        $this->assertEquals(422, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertFalse($data['success']);
        $this->assertStringContainsString('Christmas Day', $data['message']);
    }

    public function test_aics_api_booking_rejects_holiday_date(): void
    {
        $controller = app(AicsApiController::class);
        $request = Request::create('/mobile-api/aics/medical/appointments', 'POST', [
            'appointment_date' => '2026-12-25',
            'appointment_time' => '09:00',
            'interview_type'   => 'face_to_face',
        ]);

        $response = $controller->bookMedicalAppointment($request);

        $this->assertEquals(422, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertFalse($data['success']);
        $this->assertStringContainsString('Christmas Day', $data['message']);
    }

    public function test_solo_parent_api_slots_rejects_holiday_date(): void
    {
        $controller = app(SoloParentApiController::class);
        $request = Request::create('/mobile-api/solo-parent/slots', 'GET', ['date' => '2026-12-25']);

        $response = $controller->getAvailableSlots($request);

        $this->assertEquals(422, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertFalse($data['success']);
        $this->assertStringContainsString('Christmas Day', $data['message']);
    }

    public function test_solo_parent_api_booking_rejects_holiday_date(): void
    {
        $controller = app(SoloParentApiController::class);
        $request = Request::create('/mobile-api/solo-parent/appointments', 'POST', [
            'appointment_date' => '2026-12-25',
            'appointment_time' => '09:00',
            'interview_type'   => 'face_to_face',
        ]);

        $response = $controller->bookAppointment($request);

        $this->assertEquals(422, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertFalse($data['success']);
        $this->assertStringContainsString('Christmas Day', $data['message']);
    }
}
