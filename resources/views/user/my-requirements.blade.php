<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Requirements - MSWDO</title>
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

        .navbar {
            background: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
        }

        .navbar-brand, .nav-link {
            color: white !important;
        }

        .page-header {
            margin: 30px 0;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-blue);
        }

        .application-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-left: 5px solid #dee2e6;
        }

        .application-card.approved {
            border-left-color: #28a745;
        }

        .application-card.rejected {
            border-left-color: #dc3545;
        }

        .application-card.pending {
            border-left-color: #ffc107;
        }

        .application-card.in_review {
            border-left-color: #17a2b8;
        }

        .program-badge {
            background: var(--primary-blue);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
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

        .status-in_review {
            background: #d1ecf1;
            color: #0c5460;
        }

        .progress-bar-custom {
            height: 8px;
            border-radius: 4px;
            background: #e0e0e0;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-blue), var(--secondary-yellow));
            transition: width 0.3s ease;
        }

        .requirement-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 10px;
            border-left: 3px solid #dee2e6;
        }

        .requirement-item.approved {
            border-left-color: #28a745;
            background: #f0fff0;
        }

        .requirement-item.rejected {
            border-left-color: #dc3545;
            background: #fff0f0;
        }

        .requirement-item.pending {
            border-left-color: #ffc107;
        }

        .file-preview {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
        }

        .btn-view {
            background: var(--primary-blue);
            color: white;
            border: none;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
        }

        .btn-view:hover {
            background: #1a2a5c;
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/user/dashboard">
                <i class="bi bi-heart-fill"></i> MSWDO Analysis
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/user/programs">
                            <i class="bi bi-list-check"></i> Programs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/user/my-requirements">
                            <i class="bi bi-file-earmark-check"></i> My Requirements
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/user/announcements">
                            <i class="bi bi-megaphone"></i> Announcements
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/analysis">
                            <i class="bi bi-bar-chart"></i> Public Analysis
                        </a>
                    </li>
                </ul>
                <div class="d-flex">
                    <div class="user-info text-white d-flex align-items-center gap-3">
                        <i class="bi bi-person-circle"></i>
                        <span>{{ Auth::user()->full_name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-light">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="page-header">
            <h1 class="page-title">
                <i class="bi bi-file-earmark-check" style="color: var(--secondary-yellow);"></i>
                My Requirements
            </h1>
            <p class="text-muted">Track the status of your submitted requirements for each program</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif


        <!-- ADD THIS BLOCK HERE -->
@php
    $hasPendingOrApproved = false;
    $hasCompleted = false;
    foreach($requirementsData as $data) {
        if (in_array($data['overallStatus'], ['pending', 'in_review'])) {
            $hasPendingOrApproved = true;
        }
        if ($data['overallStatus'] == 'approved') {
            $hasCompleted = true;
        }
    }
@endphp

@if($hasCompleted)
    <div class="alert alert-success alert-dismissible fade show mb-4" style="background: #d4edda; border-left: 5px solid #28a745;">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <div class="d-flex align-items-center">
            <div class="me-3">
                <i class="bi bi-trophy" style="font-size: 3rem; color: #28a745;"></i>
            </div>
            <div>
                <h4 class="mb-1">🎉 Congratulations, {{ Auth::user()->full_name }}!</h4>
                <p class="mb-0">All your requirements have been <strong>APPROVED</strong>!</p>
                <p class="mb-2">Please proceed to the <strong>MSWDO Office</strong> to complete your application.</p>
                <hr class="my-2">
                <div class="row mt-2">
                    <div class="col-md-6">
                        <i class="bi bi-geo-alt-fill"></i> <strong>Location:</strong> Municipal Social Welfare and Development Office, Majayjay Municipal Hall
                    </div>
                    <div class="col-md-6">
                        <i class="bi bi-file-text-fill"></i> <strong>Bring:</strong> Your submitted requirements (printed copies)
                    </div>
                </div>
                <div class="mt-2">
                    <i class="bi bi-info-circle-fill"></i> <strong>Office Hours:</strong> Monday - Friday, 8:00 AM - 5:00 PM
                </div>
            </div>
        </div>
    </div>
@elseif($hasPendingOrApproved)
    <div class="alert alert-warning alert-dismissible fade show mb-4">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <i class="bi bi-info-circle"></i>
        <strong>You have an active application.</strong> You cannot apply for another program until your current application is completed or rejected.
    </div>
@endif
<!-- END OF ADDED BLOCK -->

        @if(count($requirementsData) > 0)
            @foreach($requirementsData as $data)
                @php
                    $app = $data['application'];
                    $overallStatus = $data['overallStatus'];
                    $totalReq = $data['totalRequirements'];
                    $uploaded = $data['uploadedCount'];
                    $approved = $data['approvedCount'];
                    $rejected = $data['rejectedCount'];
                    $pending = $data['pendingCount'];
                    $percentComplete = $totalReq > 0 ? ($approved / $totalReq) * 100 : 0;
                @endphp

                <div class="application-card {{ $overallStatus }}">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h4 class="mb-1">{{ str_replace('_', ' ', $app->program_type) }}</h4>
                                    <span class="program-badge">Applied: {{ $app->application_date->format('M d, Y') }}</span>
                                </div>
                                <div>
                                    <span class="status-badge status-{{ $overallStatus }}">
                                        <i class="bi bi-{{ $overallStatus == 'approved' ? 'check-circle' : ($overallStatus == 'rejected' ? 'x-circle' : ($overallStatus == 'in_review' ? 'hourglass-split' : 'clock')) }}"></i>
                                        {{ ucfirst(str_replace('_', ' ', $overallStatus)) }}
                                    </span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <small class="text-muted">Total Requirements</small>
                                    <div><strong>{{ $totalReq }}</strong></div>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Uploaded</small>
                                    <div><strong class="text-success">{{ $uploaded }}</strong></div>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Approved</small>
                                    <div><strong class="text-success">{{ $approved }}</strong></div>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Rejected</small>
                                    <div><strong class="text-danger">{{ $rejected }}</strong></div>
                                </div>
                            </div>

                            <div class="progress-bar-custom mb-3">
                                <div class="progress-fill" style="width: {{ $percentComplete }}%"></div>
                            </div>
                            <small class="text-muted">Overall Progress: {{ round($percentComplete) }}% Complete</small>

                            <hr class="my-3">

                            <h6 class="mb-3">Submitted Documents</h6>
                            
                            @foreach($data['fileUploads'] as $file)
                                @php
                                    $status = $file->status;
                                    $hasFile = $file->file_path;
                                @endphp
<div class="requirement-item {{ $status }}">
    <div class="row align-items-center">
        <div class="col-md-5">
            <strong>{{ $file->requirement_name }}</strong>
        </div>
        <div class="col-md-3">
            <span class="status-badge status-{{ $status }}">
                <i class="bi bi-{{ $status == 'approved' ? 'check-circle' : ($status == 'rejected' ? 'x-circle' : 'clock') }}"></i>
                {{ ucfirst($status) }}
            </span>
        </div>
        <div class="col-md-4 text-end">
            @if($hasFile)
                @php
                    $ext = pathinfo($file->file_path, PATHINFO_EXTENSION);
                @endphp
                @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                    <img src="{{ asset('storage/' . $file->file_path) }}" class="file-preview" 
                         onclick="window.open('{{ asset('storage/' . $file->file_path) }}')">
                @else
                    <i class="bi bi-file-pdf" style="font-size: 2rem; color: #dc3545; cursor: pointer;" 
                       onclick="window.open('{{ asset('storage/' . $file->file_path) }}')"></i>
                @endif
                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="btn-view ms-2">
                    <i class="bi bi-eye"></i> View
                </a>
            @else
                <span class="text-muted">
                    <i class="bi bi-cloud-upload"></i> Not uploaded
                </span>
            @endif
        </div>
    </div>
    
    <!-- SHOW ADMIN REMARKS -->
    @if($file->admin_remarks)
        <div class="text-danger small mt-2">
            <i class="bi bi-chat"></i> <strong>Admin Remarks:</strong> {{ $file->admin_remarks }}
        </div>
    @endif
    
    <!-- RE-UPLOAD SECTION (only for rejected files) -->
    @if($status == 'rejected')
        <div class="mt-3 pt-2 border-top">
            <form action="{{ route('user.resubmit-requirement', $file->id) }}" method="POST" enctype="multipart/form-data" class="reupload-form">
                @csrf
                @method('PUT')
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <input type="file" name="file" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.pdf" required>
                        <small class="text-muted">Max 5MB. Allowed: JPG, PNG, PDF</small>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-sm btn-warning w-100">
                            <i class="bi bi-arrow-repeat"></i> Re-upload Document
                        </button>
                    </div>
                </div>
            </form>
        </div>
    @endif
</div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                <h5 class="mt-3">No requirements submitted yet</h5>
                <p class="text-muted">Click "Programs" to apply for a program and submit your requirements.</p>
                <a href="{{ route('user.programs') }}" class="btn btn-primary mt-2">
                    <i class="bi bi-grid-3x3-gap-fill"></i> Browse Programs
                </a>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>