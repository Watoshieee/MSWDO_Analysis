@php $bp = $bookingPrefix ?? 'bk'; @endphp
@if(!empty($isSoloParentBeneficiary))
<div class="sp-booking-card sp-beneficiary">
    <div class="sp-booking-header">
        <div class="sp-booking-title">Solo Parent Beneficiary</div>
    </div>
    <div class="sp-booking-body">
        May na-release na / beneficiary na ang iyong Solo Parent application, kaya disabled na ang new appointment at re-application.
    </div>
</div>
@elseif($appointment && in_array($appointment->status, ['pending','confirmed']))
<div class="sp-booking-card">
    <div class="sp-booking-header">
        <div style="flex:1;">
            <div class="sp-booking-title">Your Appointment</div>
            <div class="sp-booking-sub">Solo Parent ID Application</div>
        </div>
        {!! $appointment->status_badge !!}
    </div>
    <div class="sp-booking-body">
        <div class="sp-appt-grid">
            <div class="sp-appt-tile">
                <div class="sp-appt-label">DATE</div>
                <div class="sp-appt-val">{{ $appointment->formatted_date }}</div>
            </div>
            <div class="sp-appt-tile">
                <div class="sp-appt-label">TIME</div>
                <div class="sp-appt-val">{{ $appointment->formatted_time }}</div>
            </div>
            <div class="sp-appt-tile">
                <div class="sp-appt-label">TYPE</div>
                <div class="sp-appt-val">{{ $appointment->interview_label }}</div>
            </div>
        </div>
        @if($appointment->admin_notes)
        <div class="sp-note yellow">
            <strong>Admin Note:</strong> {{ $appointment->admin_notes }}
        </div>
        @endif
        @if($appointment->appointment_date->isTomorrow())
        <div class="sp-note reminder">
            <strong>Reminder:</strong> Your appointment is TOMORROW! Please be ready.
        </div>
        @endif
        <div class="sp-appt-actions">
            <form method="POST" action="{{ route('user.appointments.cancel', $appointment->id) }}" id="cancelForm-{{ $bp }}">
                @csrf
                <input type="hidden" name="cancel_reason" id="cancelReasonInput-{{ $bp }}">
                <button type="button" onclick="showCancelModal('{{ $bp }}')" class="sp-btn sp-btn-danger">Cancel Appointment</button>
            </form>
            @if($appointment->reschedule_status === 'pending')
                <span class="sp-btn sp-btn-wait">Waiting for Approval</span>
            @else
                <button type="button" onclick="showRescheduleModal()" class="sp-btn sp-btn-outline">Request Reschedule</button>
            @endif
        </div>
    </div>
</div>
@else
<div class="sp-booking-card" id="bookingCard-{{ $bp }}">
    <div class="sp-booking-header">
        <div class="sp-booking-icon">📅</div>
        <div>
            <div class="sp-booking-title">Schedule an Appointment</div>
            <div class="sp-booking-sub">Book your interview slot with the MSWDO</div>
        </div>
    </div>
    <div class="sp-booking-body">
        <div class="sp-office-hours">
            Office hours: <strong>Monday – Friday, 8:00 AM – 5:00 PM</strong> (lunch 12:00–1:00 PM excluded) · Max 5 appointments per time slot.
        </div>

        <form id="apptForm-{{ $bp }}" method="POST" action="{{ route('user.appointments.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="sp-label">Select Date <span class="text-danger">*</span></label>
                    <input type="date" id="apptDate-{{ $bp }}" name="appointment_date"
                           min="{{ $minDate }}" max="{{ $maxDate }}"
                           class="form-control sp-input" required>
                    <div class="sp-hint">Weekdays only (Mon–Fri)</div>
                </div>
                <div class="col-md-4">
                    <label class="sp-label">Select Time Slot <span class="text-danger">*</span></label>
                    <select id="apptTime-{{ $bp }}" name="appointment_time" class="form-control sp-input" required disabled>
                        <option value="">Select date first</option>
                    </select>
                    <div id="slotMsg-{{ $bp }}" class="sp-hint"></div>
                </div>
                <div class="col-md-4">
                    <label class="sp-label">Interview Type <span class="text-danger">*</span></label>
                    <select name="interview_type" class="form-control sp-input" required>
                        <option value="face_to_face">Face-to-Face</option>
                        <option value="online">Online (via phone call)</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="sp-label">Additional Notes (optional)</label>
                    <textarea name="user_notes" rows="2" class="form-control sp-input"
                              placeholder="Any concerns or special requests…" maxlength="500"></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" id="apptSubmitBtn-{{ $bp }}" class="sp-btn sp-btn-primary">Book Appointment</button>
                </div>
            </div>
        </form>
    </div>
</div>

@if($appointment && $appointment->status === 'rejected')
<div class="sp-note rejected">
    Your previous appointment was <strong>rejected</strong>. You may book a new slot above.
    @if($appointment->admin_notes)<br>Admin reason: {{ $appointment->admin_notes }}@endif
</div>
@endif
@endif
