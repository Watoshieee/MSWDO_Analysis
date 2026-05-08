{{--
    Reusable Image Review Card Component
    
    Props:
    - $file: File upload object with properties:
        - id: File ID
        - requirement_name: Document title
        - file_path: Path to uploaded file
        - status: pending|approved|rejected
        - uploaded_at: Upload timestamp
        - admin_remarks: Admin notes (optional)
        - user: User object with full_name property
    - $fileUrl: Secure URL to view the file
--}}

@php
    $status = $file->status ?? 'pending';
    $ext = strtolower(pathinfo($file->file_path, PATHINFO_EXTENSION));
    $isImage = in_array($ext, ['jpg','jpeg','png','webp','gif']);
@endphp

<div class="req-review-card req-status-{{ $status }}">
    {{-- Image Preview --}}
    <div class="req-card-image">
        @if($isImage)
            <img src="{{ $fileUrl }}" 
                 alt="{{ $file->requirement_name }}"
                 class="req-img-preview"
                 onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 200 200%22%3E%3Crect fill=%22%23e2e8f0%22 width=%22200%22 height=%22200%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 fill=%22%2394a3b8%22 font-size=%2214%22%3ENo Image%3C/text%3E%3C/svg%3E'">
        @else
            <div class="req-file-icon">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                    <polyline points="13 2 13 9 20 9"></polyline>
                </svg>
                <div class="req-file-ext">{{ strtoupper($ext) }}</div>
            </div>
        @endif
    </div>

    {{-- Card Content --}}
    <div class="req-card-content">
        {{-- User Info --}}
        <div class="req-user-info">
            <div class="req-user-icon">👤</div>
            <div class="req-user-name">{{ $file->user->full_name ?? 'Unknown User' }}</div>
        </div>

        {{-- Requirement Title --}}
        <div class="req-title">{{ $file->requirement_name }}</div>

        {{-- Date Submitted --}}
        <div class="req-date">
            📅 {{ $file->uploaded_at ? \Carbon\Carbon::parse($file->uploaded_at)->format('M d, Y h:i A') : 'N/A' }}
        </div>

        {{-- Status Badge --}}
        <div class="req-status-badge req-badge-{{ $status }}">
            @if($status === 'approved')
                ✅ Approved
            @elseif($status === 'rejected')
                ❌ Rejected
            @else
                🟡 Pending
            @endif
        </div>

        {{-- Admin Remarks (if rejected) --}}
        @if($file->admin_remarks)
            <div class="req-remarks">
                <strong>Admin Note:</strong> {{ $file->admin_remarks }}
            </div>
        @endif

        {{-- Action Buttons --}}
        <div class="req-actions">
            @if($status === 'pending')
                {{-- Approve Button --}}
                <form action="{{ route('admin.update-file-status', $file->id) }}" method="POST" class="req-action-form">
                    @csrf
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" class="req-btn req-btn-approve">
                        ✅ Approve
                    </button>
                </form>

                {{-- Decline Button --}}
                <button type="button" 
                        class="req-btn req-btn-decline"
                        data-bs-toggle="modal"
                        data-bs-target="#declineModal"
                        data-file-id="{{ $file->id }}"
                        data-file-name="{{ $file->requirement_name }}">
                    ❌ Decline
                </button>
            @elseif($status === 'approved')
                <div class="req-status-text req-text-approved">✔ Already Approved</div>
            @elseif($status === 'rejected')
                <div class="req-status-text req-text-rejected">⏳ Waiting for Re-upload</div>
            @endif
        </div>
    </div>
</div>

<style>
/* ═══════════════════════════════════════════════════════════════
   REQUIREMENT REVIEW CARD - Modern Admin UI
   ═══════════════════════════════════════════════════════════════ */

.req-review-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
    transition: all 0.3s ease;
    border: 2px solid #e2e8f0;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.req-review-card:hover {
    box-shadow: 0 8px 24px rgba(44, 62, 143, 0.12);
    transform: translateY(-4px);
}

/* Status Border Colors */
.req-status-pending {
    border-left: 5px solid #fdb913;
}

.req-status-approved {
    border-left: 5px solid #28a745;
}

.req-status-rejected {
    border-left: 5px solid #dc3545;
}

/* Image Preview Section */
.req-card-image {
    width: 100%;
    height: 220px;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    position: relative;
}

.req-img-preview {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.req-review-card:hover .req-img-preview {
    transform: scale(1.05);
}

.req-file-icon {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    color: #94a3b8;
}

.req-file-ext {
    font-size: 0.75rem;
    font-weight: 700;
    color: #64748b;
    background: white;
    padding: 4px 12px;
    border-radius: 20px;
}

/* Card Content */
.req-card-content {
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    flex: 1;
}

/* User Info */
.req-user-info {
    display: flex;
    align-items: center;
    gap: 8px;
}

.req-user-icon {
    font-size: 1.2rem;
}

.req-user-name {
    font-size: 0.85rem;
    font-weight: 600;
    color: #475569;
}

/* Requirement Title */
.req-title {
    font-size: 1rem;
    font-weight: 800;
    color: #1e293b;
    line-height: 1.3;
    min-height: 2.6em;
}

/* Date */
.req-date {
    font-size: 0.8rem;
    color: #64748b;
    font-weight: 500;
}

/* Status Badge */
.req-status-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 700;
    display: inline-block;
    width: fit-content;
}

.req-badge-pending {
    background: #fff3cd;
    color: #856404;
}

.req-badge-approved {
    background: #d4edda;
    color: #155724;
}

.req-badge-rejected {
    background: #f8d7da;
    color: #721c24;
}

/* Admin Remarks */
.req-remarks {
    background: #fff3cd;
    border-left: 3px solid #fdb913;
    padding: 10px 12px;
    border-radius: 8px;
    font-size: 0.8rem;
    color: #856404;
    line-height: 1.4;
}

/* Action Buttons */
.req-actions {
    display: flex;
    gap: 8px;
    margin-top: auto;
    padding-top: 8px;
}

.req-action-form {
    flex: 1;
}

.req-btn {
    width: 100%;
    border: none;
    border-radius: 10px;
    padding: 10px 16px;
    font-weight: 700;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.req-btn-approve {
    background: #d4edda;
    color: #155724;
    flex: 1;
}

.req-btn-approve:hover {
    background: #28a745;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.req-btn-decline {
    background: #f8d7da;
    color: #721c24;
    flex: 1;
}

.req-btn-decline:hover {
    background: #dc3545;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

/* Status Text (for approved/rejected states) */
.req-status-text {
    font-size: 0.85rem;
    font-weight: 700;
    text-align: center;
    padding: 10px;
    border-radius: 8px;
}

.req-text-approved {
    color: #28a745;
    background: #f6fff6;
}

.req-text-rejected {
    color: #856404;
    background: #fff3cd;
}

/* Responsive Grid Layout */
.req-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 24px;
    margin-top: 24px;
}

@media (max-width: 768px) {
    .req-grid {
        grid-template-columns: 1fr;
    }
    
    .req-card-image {
        height: 180px;
    }
}
</style>
