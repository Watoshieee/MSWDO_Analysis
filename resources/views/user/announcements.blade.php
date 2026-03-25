<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Announcements - MSWDO</title>
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

        .announcement-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-left: 5px solid;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .announcement-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .announcement-card.info {
            border-left-color: #17a2b8;
        }

        .announcement-card.warning {
            border-left-color: #ffc107;
        }

        .announcement-card.success {
            border-left-color: #28a745;
        }

        .announcement-card.danger {
            border-left-color: #dc3545;
        }

        .announcement-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .announcement-preview {
            color: #6c757d;
            font-size: 0.9rem;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .announcement-date {
            font-size: 0.75rem;
            color: #adb5bd;
            margin-top: 10px;
        }

        .badge-type {
            font-size: 0.7rem;
            padding: 3px 8px;
            border-radius: 20px;
        }

        .modal-content {
            border-radius: 16px;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-blue) 0%, #1A2A5C 100%);
            color: white;
            border-radius: 16px 16px 0 0;
        }

        .modal-title {
            font-weight: 600;
        }

        .btn-close-white {
            filter: brightness(0) invert(1);
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
                        <a class="nav-link" href="/user/programs">
                            <i class="bi bi-list-check"></i> Programs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/user/my-requirements">
                            <i class="bi bi-file-earmark-check"></i> My Requirements
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/user/announcements">
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
                <i class="bi bi-megaphone" style="color: var(--secondary-yellow);"></i>
                Announcements
            </h1>
            <p class="text-muted">Important updates and reminders from MSWDO</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(count($announcements) > 0)
            @foreach($announcements as $announcement)
                <div class="announcement-card {{ $announcement->type }}" onclick="showAnnouncement({{ $announcement->id }})">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="announcement-title">{{ $announcement->title }}</div>
                            <div class="announcement-preview">{{ Str::limit(strip_tags($announcement->content), 150) }}</div>
                            <div class="announcement-date">
                                <i class="bi bi-calendar"></i> {{ $announcement->created_at->format('F d, Y') }}
                                <span class="badge-type badge bg-{{ $announcement->type }} ms-2">{{ ucfirst($announcement->type) }}</span>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right" style="font-size: 1.2rem; color: #adb5bd;"></i>
                    </div>
                </div>
            @endforeach
            
            <div class="mt-4">
                {{ $announcements->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-megaphone" style="font-size: 4rem; color: #ccc;"></i>
                <h5 class="mt-3">No announcements yet</h5>
                <p class="text-muted">Check back later for updates from MSWDO.</p>
            </div>
        @endif
    </div>

    <!-- Modal for full announcement -->
    <div class="modal fade" id="announcementModal" tabindex="-1" aria-labelledby="announcementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="announcementModalLabel">Announcement</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Store announcements data
        const announcementsData = @json($announcements);
        
        function showAnnouncement(id) {
            const announcement = announcementsData.find(a => a.id === id);
            if (announcement) {
                let typeColor = '';
                switch(announcement.type) {
                    case 'success':
                        typeColor = '#28a745';
                        break;
                    case 'warning':
                        typeColor = '#ffc107';
                        break;
                    case 'danger':
                        typeColor = '#dc3545';
                        break;
                    default:
                        typeColor = '#17a2b8';
                }
                
                const modalBody = document.getElementById('modalBody');
                modalBody.innerHTML = `
                    <div class="mb-3">
                        <span class="badge" style="background: ${typeColor}; color: white; padding: 5px 12px;">
                            <i class="bi bi-${announcement.type == 'success' ? 'check-circle' : (announcement.type == 'warning' ? 'exclamation-triangle' : (announcement.type == 'danger' ? 'x-circle' : 'info-circle'))}"></i>
                            ${announcement.type.toUpperCase()}
                        </span>
                    </div>
                    <h4 class="mb-3">${announcement.title}</h4>
                    <div class="announcement-full-content" style="line-height: 1.6; color: #333;">
                        ${announcement.content}
                    </div>
                    <hr>
                    <div class="text-muted small">
                        <i class="bi bi-calendar"></i> Posted on: ${new Date(announcement.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}
                    </div>
                `;
                
                const modal = new bootstrap.Modal(document.getElementById('announcementModal'));
                modal.show();
            }
        }
    </script>
</body>
</html>