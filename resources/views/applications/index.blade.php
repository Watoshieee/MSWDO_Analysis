<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Management - MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            /* Blue as MAIN color - Soft and calming */
            --primary-blue: #2C3E8F;
            --primary-blue-light: #E5EEFF;
            --primary-blue-soft: #5D7BB9;
            
            /* Yellow as SECONDARY - Warm and inviting */
            --secondary-yellow: #FDB913;
            --secondary-yellow-light: #FFF3D6;
            
            /* Red as MINIMAL accent - Only for rejected/delete */
            --accent-red: #C41E24;
            --accent-red-light: #FCE8E8;
            
            /* Status Colors - Maintained proper colors */
            --status-pending: #FDB913;
            --status-approved: #28a745;
            --status-rejected: #C41E24;
            
            /* Gradients */
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --secondary-gradient: linear-gradient(135deg, #FDB913 0%, #E5A500 100%);
            
            /* Backgrounds */
            --bg-light: #F8FAFC;
            --bg-white: #FFFFFF;
            --bg-soft-blue: #F0F5FF;
            --bg-soft-yellow: #FFFAF0;
            
            /* Text Colors */
            --text-dark: #1E293B;
            --text-soft: #475569;
            --text-light: #64748B;
            
            /* Borders */
            --border-light: #E2E8F0;
        }

        body {
            background: var(--bg-light);
            font-family: 'Inter', 'Segoe UI', sans-serif;
            color: var(--text-dark);
        }

        /* Navbar */
        .navbar {
            background: var(--primary-gradient) !important;
            box-shadow: 0 4px 20px rgba(44, 62, 143, 0.15);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
        }

        .navbar-brand i {
            color: var(--secondary-yellow);
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 8px;
            padding: 8px 16px !important;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white !important;
        }

        .nav-link.active {
            background: var(--secondary-yellow);
            color: var(--primary-blue) !important;
            font-weight: 600;
        }

        .user-info {
            color: white;
            display: flex;
            align-items: center;
            gap: 15px;
            background: rgba(255, 255, 255, 0.1);
            padding: 8px 20px;
            border-radius: 40px;
            backdrop-filter: blur(5px);
        }

        .logout-btn {
            background: transparent;
            border: 2px solid white;
            color: white;
            border-radius: 30px;
            padding: 6px 18px;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .logout-btn:hover {
            background: var(--secondary-yellow);
            color: var(--primary-blue);
            border-color: var(--secondary-yellow);
            transform: translateY(-2px);
        }

        .btn-login {
            background: white;
            color: var(--primary-blue);
            border: 2px solid white;
            border-radius: 30px;
            padding: 8px 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: var(--secondary-yellow);
            color: var(--primary-blue);
            border-color: var(--secondary-yellow);
            transform: translateY(-2px);
        }

        .btn-register {
            background: transparent;
            border: 2px solid white;
            color: white;
            border-radius: 30px;
            padding: 8px 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            background: var(--secondary-yellow);
            color: var(--primary-blue);
            transform: translateY(-2px);
        }

        /* Header */
        .page-header {
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-blue);
            position: relative;
            padding-bottom: 15px;
        }

        .page-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 4px;
            background: var(--primary-gradient);
            border-radius: 2px;
        }

        .page-title i {
            color: var(--secondary-yellow);
        }

        /* New Application Button */
        .btn-new {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .btn-new:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(44, 62, 143, 0.3);
            color: white;
        }

        /* Filter Card */
        .filter-card {
            background: var(--bg-white);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            border: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
        }

        .filter-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        .filter-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-title i {
            color: var(--secondary-yellow);
        }

        .filter-card .form-label {
            font-weight: 600;
            color: var(--text-soft);
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .filter-card .form-control,
        .filter-card .form-select {
            border-radius: 10px;
            border: 2px solid var(--border-light);
            padding: 10px 15px;
            transition: all 0.3s ease;
        }

        .filter-card .form-control:focus,
        .filter-card .form-select:focus {
            border-color: var(--primary-blue-soft);
            box-shadow: 0 0 0 3px rgba(44, 62, 143, 0.1);
        }

        .btn-filter {
            background: var(--primary-gradient);
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
            width: 100%;
        }

        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(44, 62, 143, 0.3);
        }

        .btn-clear {
            background: var(--bg-soft-blue);
            border: 2px solid var(--primary-blue);
            color: var(--primary-blue);
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-clear:hover {
            background: var(--primary-gradient);
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
        }

        /* Table Container */
        .table-container {
            background: var(--bg-white);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            border: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
        }

        .table-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--secondary-gradient);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: var(--primary-gradient);
            color: white;
            font-weight: 600;
            border: none;
            padding: 15px;
            font-size: 0.95rem;
            white-space: nowrap;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: var(--bg-soft-blue);
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-light);
            color: var(--text-dark);
        }

        /* Status Badges - Maintained proper colors */
        .status-badge {
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }

        .status-badge.pending {
            background: var(--secondary-yellow-light);
            color: var(--secondary-yellow);
            border: 1px solid var(--secondary-yellow);
        }

        .status-badge.approved {
            background: #E1F7E1;
            color: #28a745;
            border: 1px solid #28a745;
        }

        .status-badge.rejected {
            background: var(--accent-red-light);
            color: var(--accent-red);
            border: 1px solid var(--accent-red);
        }

        /* Program Badge - Maintained */
        .program-badge {
            background: var(--bg-soft-blue);
            color: var(--primary-blue);
            padding: 5px 12px;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            border: 1px solid var(--primary-blue-soft);
        }

        .program-badge i {
            color: var(--secondary-yellow);
        }

        /* Action Buttons - Maintained proper colors */
        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .btn-view {
            background: var(--primary-blue-light);
            color: var(--primary-blue);
            border: 1px solid var(--primary-blue-soft);
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-view:hover {
            background: var(--primary-blue);
            color: white;
            transform: translateY(-2px);
        }

        .btn-edit {
            background: var(--secondary-yellow-light);
            color: var(--secondary-yellow);
            border: 1px solid var(--secondary-yellow);
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-edit:hover {
            background: var(--secondary-yellow);
            color: var(--primary-blue);
            transform: translateY(-2px);
        }

        .btn-delete {
            background: var(--accent-red-light);
            color: var(--accent-red);
            border: 1px solid var(--accent-red);
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-delete:hover {
            background: var(--accent-red);
            color: white;
            transform: translateY(-2px);
        }

        .btn-approve {
            background: #E1F7E1;
            color: #28a745;
            border: 1px solid #28a745;
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-approve:hover {
            background: #28a745;
            color: white;
            transform: translateY(-2px);
        }

        .btn-reject {
            background: var(--accent-red-light);
            color: var(--accent-red);
            border: 1px solid var(--accent-red);
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-reject:hover {
            background: var(--accent-red);
            color: white;
            transform: translateY(-2px);
        }

        /* Pagination */
        .pagination {
            margin-top: 25px;
            justify-content: center;
        }

        .page-link {
            border: none;
            margin: 0 5px;
            border-radius: 10px;
            color: var(--primary-blue);
            transition: all 0.3s ease;
            padding: 8px 15px;
            background: white;
            border: 1px solid var(--border-light);
        }

        .page-item.active .page-link {
            background: var(--primary-gradient);
            color: white;
            border-color: transparent;
        }

        .page-link:hover {
            background: var(--bg-soft-blue);
            transform: translateY(-2px);
            border-color: var(--primary-blue-soft);
        }

        /* Public View Button */
        .public-view-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            padding: 16px 32px;
            border-radius: 50px;
            box-shadow: 0 8px 25px rgba(44, 62, 143, 0.2);
            background: white;
            color: var(--primary-blue);
            border: 2px solid var(--primary-blue);
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .public-view-btn:hover {
            background: var(--primary-gradient);
            color: white;
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(44, 62, 143, 0.3);
            border-color: transparent;
        }

        .public-view-btn i {
            font-size: 1.2rem;
            color: var(--secondary-yellow);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--primary-blue-soft);
            opacity: 0.3;
        }

        .empty-state h5 {
            color: var(--primary-blue);
            margin-top: 20px;
        }

        /* Alerts */
        .alert-success {
            background: var(--bg-soft-blue);
            color: var(--primary-blue);
            border-left: 5px solid var(--primary-blue);
            border-radius: 12px;
        }

        .alert-danger {
            background: var(--accent-red-light);
            color: var(--accent-red);
            border-left: 5px solid var(--accent-red);
            border-radius: 12px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-title {
                font-size: 1.5rem;
            }
            
            .public-view-btn {
                bottom: 20px;
                right: 20px;
                padding: 12px 20px;
                font-size: 0.9rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn-view, .btn-edit, .btn-delete, .btn-approve, .btn-reject {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/analysis">
                <i class="bi bi-heart-fill"></i> MSWDO Analysis
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @auth
                        @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('applications.*') ? 'active' : '' }}" href="{{ route('user.my-applications') }}">
                                <i class="bi bi-folder-check"></i> Applications
                            </a>
                        </li>
                        @endif
                    @endauth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('analysis') ? 'active' : '' }}" href="/analysis">
                            <i class="bi bi-bar-chart"></i> Analysis
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('analysis/demographic') ? 'active' : '' }}" href="/analysis/demographic">
                            <i class="bi bi-people"></i> Demographic
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('analysis/programs') ? 'active' : '' }}" href="/analysis/programs">
                            <i class="bi bi-heart"></i> Programs
                        </a>
                    </li>
                </ul>
                <div class="d-flex">
                    @auth
                        <div class="user-info">
                            <i class="bi bi-person-circle"></i>
                            <span>{{ Auth::user()->full_name }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="logout-btn">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn-login me-2">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                        <a href="{{ route('register') }}" class="btn-register">
                            <i class="bi bi-person-plus"></i> Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center page-header">
            <h1 class="page-title">
                <i class="bi bi-folder-check"></i> 
                Application Management
            </h1>
            <a href="{{ route('user.programs') }}" class="btn-new">
                <i class="bi bi-plus-circle"></i> New Application
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filter Section -->
        <div class="filter-card">
            <div class="filter-title">
                <i class="bi bi-funnel"></i> Filter Applications
            </div>
            <form method="GET" action="{{ url()->current() }}" id="filter-form">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Program Type</label>
                        <select name="program_type" class="form-select">
                            <option value="">All Programs</option>
                            @foreach($programTypes as $type)
                                <option value="{{ $type }}" {{ request('program_type') == $type ? 'selected' : '' }}>
                                    {{ str_replace('_', ' ', $type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if(isset($municipalities) && count($municipalities) > 0)
                    <div class="col-md-3">
                        <label class="form-label">Municipality</label>
                        <select name="municipality" class="form-select">
                            <option value="">All Municipalities</option>
                            @foreach($municipalities as $municipality)
                                <option value="{{ $municipality }}" {{ request('municipality') == $municipality ? 'selected' : '' }}>
                                    {{ $municipality }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="col-md-3">
                        <label class="form-label">Barangay</label>
                        <select name="barangay" class="form-select">
                            <option value="">All Barangays</option>
                            @if(is_array($barangays))
                                @foreach($barangays as $barangay)
                                    <option value="{{ $barangay }}" {{ request('barangay') == $barangay ? 'selected' : '' }}>
                                        {{ $barangay }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Date From</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Date To</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Name, Contact #, ID..." value="{{ request('search') }}">
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn-filter">
                            <i class="bi bi-filter"></i> Apply Filters
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ url()->current() }}" class="btn-clear">
                            <i class="bi bi-eraser"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Applications Table -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Applicant</th>
                            <th>Program</th>
                            <th>Municipality</th>
                            <th>Barangay</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $app)
                        <tr id="application-row-{{ $app->id }}">
                            <td><strong>#{{ $app->id }}</strong></td>
                            <td>
                                <i class="bi bi-calendar" style="color: var(--primary-blue);"></i>
                                @if($app->application_date)
                                    {{ \Carbon\Carbon::parse($app->application_date)->format('M d, Y') }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <i class="bi bi-person" style="color: var(--secondary-yellow);"></i>
                                {{ $app->full_name }}
                                <br>
                                <small class="text-muted">
                                    <i class="bi bi-telephone" style="color: var(--primary-blue);"></i> 
                                    {{ $app->contact_number ?? 'No contact' }}
                                </small>
                            </td>
                            <td>
                                <span class="program-badge">
                                    <i class="bi bi-heart-fill"></i>
                                    {{ str_replace('_', ' ', $app->program_type) }}
                                </span>
                            </td>
                            <td><i class="bi bi-building" style="color: var(--primary-blue);"></i> {{ $app->municipality }}</td>
                            <td><i class="bi bi-pin-map" style="color: var(--secondary-yellow);"></i> {{ $app->barangay }}</td>
                            <td>
                                <span class="status-badge {{ $app->status }}">
                                    <i class="bi bi-{{ $app->status == 'approved' ? 'check-circle' : ($app->status == 'pending' ? 'clock' : 'x-circle') }}"></i>
                                    {{ ucfirst($app->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <!-- View Button -->
                                    <a href="{{ route('applications.show', $app->id) }}" 
                                       class="btn-view" title="View Details">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    
                                    <!-- Edit Button (visible for pending or admin) -->
                                    @if($app->status == 'pending' || Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                                        <a href="{{ route('applications.edit', $app->id) }}" 
                                           class="btn-edit" title="Edit Application">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                    @endif

                                    <!-- Delete Button (only for pending) -->
                                    @if($app->status == 'pending')
                                        <button class="btn-delete" 
                                                onclick="deleteApplication({{ $app->id }})"
                                                title="Delete Application">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    @endif

                                    <!-- Approve/Reject Buttons (for admin only) -->
                                    @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                                        @if($app->status == 'pending')
                                            <button class="btn-approve" 
                                                    onclick="updateStatus({{ $app->id }}, 'approved')"
                                                    title="Approve Application">
                                                <i class="bi bi-check-lg"></i> Approve
                                            </button>
                                            <button class="btn-reject" 
                                                    onclick="updateStatus({{ $app->id }}, 'rejected')"
                                                    title="Reject Application">
                                                <i class="bi bi-x-lg"></i> Reject
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <h5>No applications found</h5>
                                <a href="{{ route('user.programs') }}" class="btn-new mt-3">
                                    <i class="bi bi-plus-circle"></i> Create Your First Application
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                @if(method_exists($applications, 'links'))
                    {{ $applications->links() }}
                @endif
            </div>
        </div>
    </div>

    <!-- Public View Button -->
    <a href="/analysis" class="public-view-btn">
        <i class="bi bi-eye"></i>
        Public View
        <small>(View Analysis)</small>
    </a>

    <!-- Delete Form (Hidden) -->
    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update Status Function
        function updateStatus(id, status) {
            if (!confirm('Are you sure you want to ' + status.toUpperCase() + ' this application?')) {
                return;
            }

            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            button.disabled = true;

            fetch('/applications/' + id + '/status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: status })
            })
            .then(async response => {
                const text = await response.text();
                try {
                    const data = JSON.parse(text);
                    if (!response.ok) {
                        throw new Error(data.message || 'Server error');
                    }
                    return data;
                } catch (e) {
                    console.error('Server response:', text);
                    throw new Error('Invalid JSON response from server');
                }
            })
            .then(data => {
                if (data.success) {
                    alert('✅ Application ' + status + ' successfully!');
                    location.reload();
                } else {
                    alert('❌ Error: ' + (data.message || 'Failed to update status'));
                }
            })
            .catch(error => {
                alert('❌ Error: ' + error.message);
                console.error('Error details:', error);
            })
            .finally(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }

        // Delete Application Function
        function deleteApplication(id) {
            if (!confirm('⚠️ Are you sure you want to DELETE this application? This action cannot be undone.')) {
                return;
            }

            const form = document.getElementById('delete-form');
            form.action = '/applications/' + id;
            form.submit();
        }

        // Auto-submit filters
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.getElementById('filter-form');
            
            document.querySelectorAll('select[name="status"], select[name="program_type"], select[name="municipality"], select[name="barangay"]').forEach(select => {
                select.addEventListener('change', () => filterForm.submit());
            });

            document.querySelectorAll('input[type="date"]').forEach(input => {
                input.addEventListener('change', () => filterForm.submit());
            });

            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                let searchTimeout;
                searchInput.addEventListener('keyup', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => filterForm.submit(), 1000);
                });
            }
        });
    </script>
</body>
</html>