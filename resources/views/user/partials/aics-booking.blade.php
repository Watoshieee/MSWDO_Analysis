@php $bp = $bookingPrefix ?? 'bk'; @endphp
@if($appointment && in_array($appointment->status, ['pending','confirmed']))
<div class="aics-booking-card">
    <div class="aics-booking-header">
        <div style="flex:1;">
            <div class="aics-booking-title">Your Appointment</div>
            <div class="aics-booking-sub">{{ $aicsProgramLabel }}</div>
        </div>
        {!! $appointment->status_badge !!}
    </div>
    <div class="aics-booking-body">
        <div class="aics-appt-grid">
            <div class="aics-appt-tile">
                <div class="aics-appt-label">DATE</div>
                <div class="aics-appt-val">{{ $appointment->formatted_date }}</div>
            </div>
            <div class="aics-appt-tile">
                <div class="aics-appt-label">TIME</div>
                <div class="aics-appt-val">{{ $appointment->formatted_time }}</div>
            </div>
            <div class="aics-appt-tile">
                <div class="aics-appt-label">TYPE</div>
                <div class="aics-appt-val">{{ $appointment->interview_label }}</div>
            </div>
        </div>
        @if($appointment->admin_notes)
        <div class="aics-note yellow">
            <strong>Admin Note:</strong> {{ $appointment->admin_notes }}
        </div>
        @endif
        @if($appointment->appointment_date->isTomorrow())
        <div class="aics-note reminder">
            <strong>Reminder:</strong> Your appointment is TOMORROW! Please be ready.
        </div>
        @endif
        @if($appointment->cancellation_status === 'pending')
        <div class="aics-note yellow">
            <strong>Cancellation Pending:</strong> Your cancellation request is waiting for admin approval.
        </div>
        @endif
        <div class="aics-appt-actions">
            <form method="POST" action="{{ route('user.appointments.cancel', $appointment->id) }}" id="cancelForm-{{ $bp }}">
                @csrf
                <input type="hidden" name="cancel_reason" id="cancelReasonInput-{{ $bp }}">
                @if($appointment->cancellation_status !== 'pending')
                <button type="button" onclick="showCancelModal('{{ $bp }}')" class="aics-btn aics-btn-danger">Cancel Appointment</button>
                @endif
            </form>
            @if($appointment->reschedule_status === 'pending')
                <span class="aics-btn aics-btn-wait">Waiting for Approval</span>
            @else
                <button type="button" onclick="showRescheduleModal()" class="aics-btn aics-btn-outline">Request Reschedule</button>
            @endif
        </div>
    </div>
</div>
@else
<div class="aics-booking-card" id="bookingCard-{{ $bp }}">
    <div class="aics-booking-header">
        <div class="aics-booking-icon">📅</div>
        <div>
            <div class="aics-booking-title">Schedule an Appointment</div>
            <div class="aics-booking-sub">Book your {{ $aicsProgramLabel }} interview slot with the MSWDO</div>
        </div>
    </div>
    <div class="aics-booking-body">
        <div class="aics-office-hours">
            Office hours: <strong>Monday – Friday, 8:00 AM – 5:00 PM</strong> (lunch 12:00–1:00 PM excluded) · Max 5 appointments per time slot.
        </div>
        @if(isset($appointment) && $appointment && $appointment->status === 'rejected')
        <div class="aics-note rejected">
            Your previous appointment was <strong>rejected</strong>. You may book a new slot below.
            @if($appointment->admin_notes)<br>Admin reason: {{ $appointment->admin_notes }}@endif
        </div>
        @endif
        <form id="apptForm-{{ $bp }}" method="POST" action="{{ route('user.appointments.store') }}">
            @csrf
            <input type="hidden" name="program_type" value="{{ $aicsProgramType }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="aics-label">Select Date <span class="text-danger">*</span></label>
                    <input type="date" id="apptDate-{{ $bp }}" name="appointment_date"
                           min="{{ $minDate }}" max="{{ $maxDate }}"
                           class="form-control aics-input" required>
                    <div class="aics-hint">Weekdays only (Mon–Fri)</div>
                </div>
                <div class="col-md-4">
                    <label class="aics-label">Select Time Slot <span class="text-danger">*</span></label>
                    <select id="apptTime-{{ $bp }}" name="appointment_time" class="form-control aics-input" required disabled>
                        <option value="">Select date first</option>
                    </select>
                    <div id="slotMsg-{{ $bp }}" class="aics-hint"></div>
                </div>
                <div class="col-md-4">
                    <label class="aics-label">Interview Type <span class="text-danger">*</span></label>
                    <select name="interview_type" class="form-control aics-input" required>
                        <option value="face_to_face">Face-to-Face</option>
                        <option value="online">Online (via phone call)</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="aics-label">Additional Notes (optional)</label>
                    <textarea name="user_notes" rows="2" class="form-control aics-input"
                              placeholder="Any concerns or special requests…" maxlength="500"></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" id="apptSubmitBtn-{{ $bp }}" class="aics-btn aics-btn-primary">Book Appointment</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
