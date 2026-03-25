<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Upload Requirements - MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2C3E8F;
            --secondary-yellow: #FDB913;
        }
        
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .step {
            flex: 1;
            text-align: center;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
            margin: 0 5px;
            color: #6c757d;
        }
        
        .step.active {
            background: var(--primary-blue);
            color: white;
        }
        
        .step.completed {
            background: #28a745;
            color: white;
        }
        
        .step i {
            font-size: 1.5rem;
            display: block;
            margin-bottom: 5px;
        }
        
        .program-header {
            background: linear-gradient(135deg, var(--primary-blue) 0%, #1a2a5c 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
        }
        
        .requirement-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-left: 4px solid #dee2e6;
            transition: all 0.3s ease;
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
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
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
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            border: 1px solid #ddd;
        }
        
        .upload-area {
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .upload-area:hover {
            border-color: var(--primary-blue);
            background: #f8f9fa;
        }
        
        .requirement-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
            font-size: 1rem;
        }
        
        .btn-back {
            background: #6c757d;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-back:hover {
            background: #5a6268;
            color: white;
        }
        
        .progress-info {
            background: white;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .progress-bar-custom {
            height: 10px;
            border-radius: 5px;
            background: #e0e0e0;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-blue), var(--secondary-yellow));
            transition: width 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <!-- Progress Steps -->
        <div class="progress-steps">
            <div class="step completed">
                <i class="bi bi-check-circle"></i>
                1. Application Form
            </div>
            <div class="step active">
                <i class="bi bi-file-text"></i>
                2. Upload Requirements
            </div>
            <div class="step">
                <i class="bi bi-check2-circle"></i>
                3. For Review
            </div>
        </div>

        <!-- Program Header -->
        <div class="program-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="mb-1">
                        <i class="bi bi-folder-check"></i> 
                        {{ str_replace('_', ' ', $application->program_type) }}
                    </h3>
                    <p class="mb-0">Applicant: {{ $application->full_name }}</p>
                    <p class="mb-0">Barangay: {{ $application->barangay }}</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('user.my-applications') }}" class="btn-back">
                        <i class="bi bi-arrow-left"></i> Back to Applications
                    </a>
                </div>
            </div>
        </div>

        <!-- Progress Info -->
        <div class="progress-info">
            @php
                $totalRequirements = $requirements->count();
                $uploadedRequirements = $fileUploads->where('file_path', '!=', null)->count();
                $approvedRequirements = $fileUploads->where('status', 'approved')->count();
                $percentComplete = $totalRequirements > 0 ? ($approvedRequirements / $totalRequirements) * 100 : 0;
            @endphp
            <div class="row">
                <div class="col-md-4">
                    <strong>Total Requirements:</strong> {{ $totalRequirements }}
                </div>
                <div class="col-md-4">
                    <strong>Uploaded:</strong> {{ $uploadedRequirements }}
                </div>
                <div class="col-md-4">
                    <strong>Approved:</strong> {{ $approvedRequirements }}
                </div>
            </div>
            <div class="progress-bar-custom mt-2">
                <div class="progress-fill" style="width: {{ $percentComplete }}%"></div>
            </div>
            <small class="text-muted mt-2 d-block">Overall Progress: {{ round($percentComplete) }}% Complete</small>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Requirements List -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-list-check"></i> Required Documents for {{ str_replace('_', ' ', $application->program_type) }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach($requirements as $req)
                            @php
                                $fileUpload = $fileUploads->where('requirement_name', $req->requirement_name)->first();
                                $status = $fileUpload ? $fileUpload->status : 'pending';
                                $statusClass = $status == 'approved' ? 'approved' : ($status == 'rejected' ? 'rejected' : 'pending');
                                $statusBadge = $status == 'approved' ? 'status-approved' : ($status == 'rejected' ? 'status-rejected' : 'status-pending');
                                $hasFile = $fileUpload && $fileUpload->file_path;
                            @endphp
                            
                            <div class="requirement-card {{ $statusClass }}">
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <div class="requirement-name">{{ $req->requirement_name }}</div>
                                        <span class="status-badge {{ $statusBadge }}">
                                            <i class="bi bi-{{ $status == 'approved' ? 'check-circle' : ($status == 'rejected' ? 'x-circle' : 'clock') }}"></i>
                                            {{ ucfirst($status) }}
                                        </span>
                                        @if($fileUpload && $fileUpload->remarks)
                                            <div class="text-danger small mt-1">
                                                <i class="bi bi-chat"></i> {{ $fileUpload->remarks }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        @if($hasFile)
                                            <div class="d-flex align-items-center gap-3">
                                                @php
                                                    $ext = pathinfo($fileUpload->file_path, PATHINFO_EXTENSION);
                                                @endphp
                                                @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                                                    <img src="{{ asset('storage/' . $fileUpload->file_path) }}" class="file-preview" onclick="window.open('{{ asset('storage/' . $fileUpload->file_path) }}')">
                                                @else
                                                    <div class="file-preview d-flex align-items-center justify-content-center bg-light" style="cursor:pointer" onclick="window.open('{{ asset('storage/' . $fileUpload->file_path) }}')">
                                                        <i class="bi bi-file-pdf" style="font-size: 2rem; color: #dc3545;"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <small class="d-block text-muted">{{ $fileUpload->file_name ?? 'document' }}</small>
                                                    <a href="{{ asset('storage/' . $fileUpload->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-1">View</a>
                                                    @if($status == 'rejected')
                                                        <button type="button" class="btn btn-sm btn-outline-danger mt-1" onclick="deleteFile('{{ $req->requirement_name }}')">Remove</button>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <div class="upload-area" onclick="document.getElementById('file-{{ $loop->index }}').click()">
                                                <i class="bi bi-cloud-upload" style="font-size: 1.5rem;"></i>
                                                <p class="mb-0 small">Click to upload file</p>
                                                <small class="text-muted">JPG, PNG, PDF (Max 5MB)</small>
                                            </div>
                                            <form action="{{ route('applications.requirement.upload', $application->id) }}" method="POST" enctype="multipart/form-data" id="form-{{ $loop->index }}" style="display: none;">
                                                @csrf
                                                <input type="hidden" name="requirement_name" value="{{ $req->requirement_name }}">
                                                <input type="file" name="file" id="file-{{ $loop->index }}" accept=".jpg,.jpeg,.png,.pdf" onchange="document.getElementById('form-{{ $loop->index }}').submit()">
                                            </form>
                                        @endif
                                    </div>
                                    <div class="col-md-4 text-end">
                                        @if($hasFile && $status == 'approved')
                                            <span class="text-success">
                                                <i class="bi bi-check-circle-fill"></i> Verified
                                            </span>
                                        @elseif($hasFile && $status == 'pending')
                                            <span class="text-warning">
                                                <i class="bi bi-hourglass-split"></i> Waiting for verification
                                            </span>
                                        @elseif($hasFile && $status == 'rejected')
                                            <span class="text-danger">
                                                <i class="bi bi-x-circle"></i> Needs re-upload
                                            </span>
                                        @else
                                            <span class="text-muted">
                                                <i class="bi bi-cloud-upload"></i> Not uploaded yet
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function deleteFile(requirementName) {
            if (confirm('Are you sure you want to remove this file? You will need to upload a new one.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('applications.requirement.delete', $application->id) }}';
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="requirement_name" value="${requirementName}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>