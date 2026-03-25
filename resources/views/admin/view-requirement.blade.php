<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>View Requirements - {{ $fileMonitoring->application->full_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .requirement-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-left: 4px solid #dee2e6;
        }
        .requirement-card.approved {
            border-left-color: #28a745;
            background: #f0fff0;
        }
        .requirement-card.rejected {
            border-left-color: #dc3545;
            background: #fff0f0;
        }
        .requirement-card.pending {
            border-left-color: #ffc107;
        }
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .status-approved {
            background: #d4edda;
            color: #155724;
        }
        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .file-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            border: 1px solid #ddd;
        }
        .btn-approve {
            background: #28a745;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
        }
        .btn-reject {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
        }
        .remark-input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 5px;
        }
        .remark-input:focus {
            border-color: var(--primary-blue);
            outline: none;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <!-- Header -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Application Requirements</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Applicant:</strong> {{ $fileMonitoring->application->full_name }}</p>
                        <p><strong>Program:</strong> {{ str_replace('_', ' ', $fileMonitoring->application->program_type) }}</p>
                        <p><strong>Barangay:</strong> {{ $fileMonitoring->application->barangay ?: 'Not specified' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Municipality:</strong> {{ $fileMonitoring->municipality }}</p>
                        <p><strong>Date Submitted:</strong> {{ $fileMonitoring->created_at->format('M d, Y h:i A') }}</p>
                        <p><strong>Overall Status:</strong> 
                            <span class="status-badge status-{{ $fileMonitoring->overall_status }}">
                                {{ ucfirst($fileMonitoring->overall_status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Requirements List -->
        <h4>Required Documents</h4>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @foreach($fileMonitoring->fileUploads as $file)
            @php
                $status = $file->status;
                $hasFile = $file->file_path;
            @endphp
            <div class="requirement-card {{ $status }}" id="requirement-{{ $file->id }}">
                <div class="row">
                    <div class="col-md-4">
                        <strong>{{ $file->requirement_name }}</strong>
                        <div class="mt-2">
                            <span class="status-badge status-{{ $status }}">
                                <i class="bi bi-{{ $status == 'approved' ? 'check-circle' : ($status == 'rejected' ? 'x-circle' : 'clock') }}"></i>
                                {{ ucfirst($status) }}
                            </span>
                        </div>
                        @if($file->admin_remarks)
                            <div class="text-danger small mt-2">
                                <i class="bi bi-chat"></i> <strong>Remarks:</strong> {{ $file->admin_remarks }}
                            </div>
                        @endif
                    </div>
                    <div class="col-md-4">
                        @if($hasFile)
                            @php
                                $ext = pathinfo($file->file_path, PATHINFO_EXTENSION);
                            @endphp
                            @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                                <img src="{{ asset('storage/' . $file->file_path) }}" class="file-preview" 
                                     onclick="window.open('{{ asset('storage/' . $file->file_path) }}')">
                            @else
                                <i class="bi bi-file-pdf" style="font-size: 3rem; color: #dc3545; cursor: pointer;" 
                                   onclick="window.open('{{ asset('storage/' . $file->file_path) }}')"></i>
                            @endif
                            <div class="mt-2">
                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> View Full Document
                                </a>
                            </div>
                        @else
                            <span class="text-muted">
                                <i class="bi bi-cloud-slash"></i> No file uploaded
                            </span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        @if($status != 'approved')
                            <form action="{{ route('admin.update-file-status', $file->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" id="status-{{ $file->id }}" value="">
                                <div class="mb-2">
                                    <textarea name="admin_remarks" class="remark-input" rows="3" placeholder="Enter remarks here... (required for rejection)"></textarea>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn-approve" onclick="setStatusAndSubmit({{ $file->id }}, 'approved')">
                                        <i class="bi bi-check-lg"></i> Approve
                                    </button>
                                    <button type="button" class="btn-reject ms-2" onclick="setStatusAndSubmit({{ $file->id }}, 'rejected')">
                                        <i class="bi bi-x-lg"></i> Reject
                                    </button>
                                </div>
                            </form>

                                    <!-- ADD THIS INDICATOR HERE -->
        @if($status == 'rejected')
            <div class="text-warning small mt-3">
                <i class="bi bi-arrow-repeat"></i> <strong>Note:</strong> User can re-upload this document after rejection.
            </div>
        @endif
                        @else
                            <div class="text-success">
                                <i class="bi bi-check-circle-fill"></i> Already Approved
                            </div>
                            @if($file->admin_remarks)
                                <div class="text-muted small mt-2">
                                    <strong>Remarks:</strong> {{ $file->admin_remarks }}
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

        <div class="mt-4 text-center">
            <a href="{{ route('admin.requirements') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Requirements List
            </a>
        </div>
    </div>

    <script>
        function setStatusAndSubmit(fileId, status) {
            const form = document.querySelector(`#requirement-${fileId} form`);
            const statusInput = form.querySelector(`#status-${fileId}`);
            const remarks = form.querySelector('textarea[name="admin_remarks"]').value;
            
            if (status === 'rejected' && !remarks.trim()) {
                alert('Please provide remarks for rejection.');
                return;
            }
            
            statusInput.value = status;
            form.submit();
        }
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>