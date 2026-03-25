<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Programs - MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2C3E8F;
            --primary-blue-light: #E5EEFF;
            --secondary-yellow: #FDB913;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --bg-light: #F8FAFC;
            --bg-white: #FFFFFF;
            --border-light: #E2E8F0;
        }

        body {
            background: var(--bg-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

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
        }

        .logout-btn {
            background: transparent;
            border: 2px solid white;
            color: white;
            border-radius: 30px;
            padding: 6px 18px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: var(--secondary-yellow);
            color: var(--primary-blue);
            border-color: var(--secondary-yellow);
        }

        .page-header {
            margin: 30px 0;
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
        }

        .program-card {
            background: var(--bg-white);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
            cursor: pointer;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .program-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        .program-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(44, 62, 143, 0.1);
            border-color: var(--primary-blue);
        }

        .program-card.disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .program-card.disabled:hover {
            transform: none;
            box-shadow: none;
        }

        .program-icon {
            font-size: 2.5rem;
            color: var(--secondary-yellow);
            margin-bottom: 15px;
        }

        .program-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 10px;
        }

        .program-desc {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .apply-btn {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }

        .apply-btn:hover:not(:disabled) {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(44, 62, 143, 0.3);
        }

        .apply-btn:disabled {
            background: #adb5bd;
            cursor: not-allowed;
        }

        .alert-warning {
            background: var(--secondary-yellow-light);
            border-left: 5px solid var(--secondary-yellow);
            color: #856404;
        }

        a {
            text-decoration: none;
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
                        <a class="nav-link" href="/user/dashboard">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/user/programs">
                            <i class="bi bi-list-check"></i> Programs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/user/my-requirements">
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
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="page-header">
            <h1 class="page-title">
                <i class="bi bi-grid-3x3-gap-fill" style="color: var(--secondary-yellow);"></i>
                Available Programs
            </h1>
            <p class="text-muted">Choose a program to apply for assistance</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

@php
    $hasPendingOrApproved = \App\Models\Application::where('user_id', Auth::id())
        ->whereIn('status', ['pending', 'in_review'])
        ->exists();
    $hasCompleted = \App\Models\Application::where('user_id', Auth::id())
        ->where('status', 'approved')
        ->exists();
@endphp

@if($hasCompleted)
    <div class="alert alert-success alert-dismissible fade show mb-4">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <i class="bi bi-check-circle-fill"></i>
        <strong>Congratulations!</strong> Your application has been approved! Please proceed to the MSWDO Office to claim your benefits.
    </div>
@elseif($hasPendingOrApproved)
    <div class="alert alert-warning alert-dismissible fade show mb-4">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <i class="bi bi-info-circle"></i>
        <strong>You have an active application.</strong> You cannot apply for another program until your current application is completed or rejected.
    </div>
@endif

        <div class="row">
            <!-- 4Ps Program -->
            <div class="col-md-3 col-sm-6 mb-4">
                @if($hasPendingOrApproved)
                    <div class="program-card disabled">
                        <div class="program-icon"><i class="bi bi-people"></i></div>
                        <div class="program-title">4Ps</div>
                        <div class="program-desc">Pantawid Pamilyang Pilipino Program</div>
                        <button class="apply-btn" disabled>Apply Now <i class="bi bi-arrow-right"></i></button>
                    </div>
                @else
                    <a href="{{ route('user.apply', '4Ps') }}">
                        <div class="program-card">
                            <div class="program-icon"><i class="bi bi-people"></i></div>
                            <div class="program-title">4Ps</div>
                            <div class="program-desc">Pantawid Pamilyang Pilipino Program</div>
                            <button class="apply-btn">Apply Now <i class="bi bi-arrow-right"></i></button>
                        </div>
                    </a>
                @endif
            </div>

            <!-- Senior Citizen Program -->
            <div class="col-md-3 col-sm-6 mb-4">
                @if($hasPendingOrApproved)
                    <div class="program-card disabled">
                        <div class="program-icon"><i class="bi bi-person-standing"></i></div>
                        <div class="program-title">Senior Citizen</div>
                        <div class="program-desc">Social pension for senior citizens</div>
                        <button class="apply-btn" disabled>Apply Now <i class="bi bi-arrow-right"></i></button>
                    </div>
                @else
                    <a href="{{ route('user.apply', 'Senior_Citizen_Pension') }}">
                        <div class="program-card">
                            <div class="program-icon"><i class="bi bi-person-standing"></i></div>
                            <div class="program-title">Senior Citizen</div>
                            <div class="program-desc">Social pension for senior citizens</div>
                            <button class="apply-btn">Apply Now <i class="bi bi-arrow-right"></i></button>
                        </div>
                    </a>
                @endif
            </div>

            <!-- PWD Program -->
            <div class="col-md-3 col-sm-6 mb-4">
                @if($hasPendingOrApproved)
                    <div class="program-card disabled">
                        <div class="program-icon"><i class="bi bi-handicap"></i></div>
                        <div class="program-title">PWD Assistance</div>
                        <div class="program-desc">Assistance for persons with disability</div>
                        <button class="apply-btn" disabled>Apply Now <i class="bi bi-arrow-right"></i></button>
                    </div>
                @else
                    <a href="{{ route('user.apply', 'PWD_Assistance') }}">
                        <div class="program-card">
                            <div class="program-icon"><i class="bi bi-handicap"></i></div>
                            <div class="program-title">PWD Assistance</div>
                            <div class="program-desc">Assistance for persons with disability</div>
                            <button class="apply-btn">Apply Now <i class="bi bi-arrow-right"></i></button>
                        </div>
                    </a>
                @endif
            </div>

            <!-- Solo Parent Program -->
            <div class="col-md-3 col-sm-6 mb-4">
                @if($hasPendingOrApproved)
                    <div class="program-card disabled">
                        <div class="program-icon"><i class="bi bi-person-hearts"></i></div>
                        <div class="program-title">Solo Parent</div>
                        <div class="program-desc">Support for solo parents</div>
                        <button class="apply-btn" disabled>Apply Now <i class="bi bi-arrow-right"></i></button>
                    </div>
                @else
                    <a href="{{ route('user.apply', 'Solo_Parent') }}">
                        <div class="program-card">
                            <div class="program-icon"><i class="bi bi-person-hearts"></i></div>
                            <div class="program-title">Solo Parent</div>
                            <div class="program-desc">Support for solo parents</div>
                            <button class="apply-btn">Apply Now <i class="bi bi-arrow-right"></i></button>
                        </div>
                    </a>
                @endif
            </div>

            <!-- AICS Program -->
            <div class="col-md-3 col-sm-6 mb-4">
                @if($hasPendingOrApproved)
                    <div class="program-card disabled">
                        <div class="program-icon"><i class="bi bi-cash-stack"></i></div>
                        <div class="program-title">AICS</div>
                        <div class="program-desc">Assistance to Individuals in Crisis Situation</div>
                        <button class="apply-btn" disabled>Apply Now <i class="bi bi-arrow-right"></i></button>
                    </div>
                @else
                    <a href="{{ route('user.apply', 'AICS') }}">
                        <div class="program-card">
                            <div class="program-icon"><i class="bi bi-cash-stack"></i></div>
                            <div class="program-title">AICS</div>
                            <div class="program-desc">Assistance to Individuals in Crisis Situation</div>
                            <button class="apply-btn">Apply Now <i class="bi bi-arrow-right"></i></button>
                        </div>
                    </a>
                @endif
            </div>

            <!-- SLP Program -->
            <div class="col-md-3 col-sm-6 mb-4">
                @if($hasPendingOrApproved)
                    <div class="program-card disabled">
                        <div class="program-icon"><i class="bi bi-briefcase"></i></div>
                        <div class="program-title">SLP</div>
                        <div class="program-desc">Sustainable Livelihood Program</div>
                        <button class="apply-btn" disabled>Apply Now <i class="bi bi-arrow-right"></i></button>
                    </div>
                @else
                    <a href="{{ route('user.apply', 'SLP') }}">
                        <div class="program-card">
                            <div class="program-icon"><i class="bi bi-briefcase"></i></div>
                            <div class="program-title">SLP</div>
                            <div class="program-desc">Sustainable Livelihood Program</div>
                            <button class="apply-btn">Apply Now <i class="bi bi-arrow-right"></i></button>
                        </div>
                    </a>
                @endif
            </div>

            <!-- ESA Program -->
            <div class="col-md-3 col-sm-6 mb-4">
                @if($hasPendingOrApproved)
                    <div class="program-card disabled">
                        <div class="program-icon"><i class="bi bi-mortarboard"></i></div>
                        <div class="program-title">ESA</div>
                        <div class="program-desc">Educational Assistance</div>
                        <button class="apply-btn" disabled>Apply Now <i class="bi bi-arrow-right"></i></button>
                    </div>
                @else
                    <a href="{{ route('user.apply', 'ESA') }}">
                        <div class="program-card">
                            <div class="program-icon"><i class="bi bi-mortarboard"></i></div>
                            <div class="program-title">ESA</div>
                            <div class="program-desc">Educational Assistance</div>
                            <button class="apply-btn">Apply Now <i class="bi bi-arrow-right"></i></button>
                        </div>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>