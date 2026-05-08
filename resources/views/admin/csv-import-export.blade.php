<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CSV Import/Export - MSWDO Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @include('components.admin-colors')
    <style>
        :root {
            --primary-blue: {{ $adminPrimaryColor ?? '#2C3E8F' }};
            --secondary-yellow: {{ $adminSecondaryColor ?? '#FDB913' }};
            --accent-red: {{ $adminAccentColor ?? '#C41E24' }};
            --primary-gradient: linear-gradient(135deg, var(--primary-blue) 0%, color-mix(in srgb, var(--primary-blue) 80%, black) 100%);
            --bg-light: #F8FAFC;
        }
        
        body {
            background: var(--bg-light);
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }
        
        /* NAVBAR */
        .navbar { background: var(--primary-gradient) !important; box-shadow: 0 4px 24px rgba(44,62,143,0.18); padding: 14px 0; }
        .navbar-brand { font-weight: 800; font-size: 1.55rem; color: white !important; display: flex; align-items: center; gap: 12px; }
        .navbar-toggler { order: -1; }
        .navbar-brand { order: 0; margin-left: auto !important; margin-right: 0 !important; }
        @media (min-width: 992px) {
            .navbar-toggler { order: 0; }
            .navbar-brand { order: 0; margin-left: 0 !important; margin-right: auto !important; }
        }
        .nav-link { color: rgba(255,255,255,0.88) !important; font-weight: 600; transition: all 0.25s; border-radius: 8px; padding: 10px 18px !important; font-size: 0.85rem; white-space: nowrap; }
        .nav-link:hover { background: rgba(255,255,255,0.15); color: white !important; }
        .nav-link.active { background: var(--secondary-yellow); color: var(--primary-blue) !important; font-weight: 700; }
        .user-info { color: white; display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.1); padding: 9px 22px; border-radius: 40px; font-size: 0.9rem; font-weight: 600; }
        .logout-btn { background: transparent; border: 2px solid rgba(255,255,255,0.8); color: white; border-radius: 30px; padding: 6px 18px; font-weight: 700; transition: all 0.3s; font-size: 0.88rem; cursor: pointer; }
        .logout-btn:hover { background: var(--secondary-yellow); color: var(--primary-blue); border-color: var(--secondary-yellow); }
        
        .page-header {
            background: var(--primary-gradient);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background: white;
            border-bottom: 2px solid #e9ecef;
            padding: 1.25rem 1.5rem;
            font-weight: 700;
            color: var(--primary-blue);
        }
        
        .btn-primary {
            background: var(--primary-blue);
            border: none;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
        }
        
        .btn-primary:hover {
            background: #1A2A5C;
        }
        
        .btn-warning {
            background: var(--secondary-yellow);
            border: none;
            color: #333;
            font-weight: 600;
        }
        
        .upload-area {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            background: #f8fafc;
            transition: all 0.3s;
        }
        
        .upload-area:hover {
            border-color: var(--primary-blue);
            background: #f0f5ff;
        }
        
        .upload-area.dragover {
            border-color: var(--secondary-yellow);
            background: #fffbeb;
        }
        
        .status-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        
        .status-completed { background: #d4edda; color: #155724; }
        .status-processing { background: #fff3cd; color: #856404; }
        .status-failed { background: #f8d7da; color: #721c24; }
        .status-pending { background: #d1ecf1; color: #0c5460; }
        
        .log-table {
            font-size: 0.9rem;
        }
        
        .log-table th {
            background: #f8fafc;
            font-weight: 700;
            color: var(--primary-blue);
            border-bottom: 2px solid #e9ecef;
        }
        
        .icon-box {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 1rem;
        }
        
        .icon-box.upload { background: #e0f2fe; color: #0369a1; }
        .icon-box.download { background: #dcfce7; color: #15803d; }
        .icon-box.template { background: #fef3c7; color: #a16207; }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/admin/dashboard">
                <img src="{{ asset('images/mswd-logo.png') }}" alt="MSWD" style="width:36px;height:36px;object-fit:contain;"> MSWDO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/admin/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.requirements*') ? 'active' : '' }}" href="{{ route('admin.requirements') }}">Applications</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">Users Management</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.data.*') || request()->routeIs('admin.csv.*') ? 'active' : '' }}" href="{{ route('admin.data.dashboard') }}">Data Management</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.announcements*') ? 'active' : '' }}" href="{{ route('admin.announcements.index') }}">Announcements</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.detailed*') ? 'active' : '' }}" href="{{ route('admin.detailed-analysis') }}">Analysis</a></li>
                    <li class="nav-item"><a class="nav-link" href="/analysis/programs">Comparative Analysis</a></li>
                </ul>
                <div class="d-flex">
                    @auth
                    <div class="user-info">
                        <span>{{ Auth::user()->full_name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="logout-btn">Logout</button>
                        </form>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="mb-2"><i class="bi bi-file-earmark-spreadsheet me-2"></i>CSV Import/Export</h1>
            <p class="mb-0 opacity-75">Upload or export PSA statistical data for {{ $adminMunicipality }}</p>
        </div>
    </div>

    <div class="container">
        <!-- Alerts -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row">
            <!-- Import Section -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-upload me-2"></i>Import CSV Data
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.csv.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                            @csrf
                            
                            <!-- Data Type Selection -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Select Data Type</label>
                                <select name="import_type" class="form-select" required>
                                    <option value="">Choose data type...</option>
                                    <option value="municipality_data">Municipality Data</option>
                                    <option value="barangay_data">Barangay Data</option>
                                    <option value="program_data">Program Data</option>
                                </select>
                            </div>

                            <!-- File Upload Area -->
                            <div class="upload-area" id="uploadArea">
                                <div class="icon-box upload">
                                    <i class="bi bi-cloud-upload"></i>
                                </div>
                                <h5>Drag & Drop CSV File</h5>
                                <p class="text-muted mb-3">or click to browse</p>
                                <input type="file" name="csv_file" id="csvFile" class="d-none" accept=".csv" required>
                                <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('csvFile').click()">
                                    <i class="bi bi-folder2-open me-2"></i>Browse Files
                                </button>
                                <p class="text-muted mt-3 mb-0 small">Maximum file size: 10MB</p>
                            </div>

                            <!-- Selected File Display -->
                            <div id="selectedFile" class="mt-3 d-none">
                                <div class="alert alert-info d-flex align-items-center">
                                    <i class="bi bi-file-earmark-text fs-4 me-3"></i>
                                    <div class="flex-grow-1">
                                        <strong id="fileName"></strong>
                                        <div class="small text-muted" id="fileSize"></div>
                                    </div>
                                    <button type="button" class="btn-close" onclick="clearFile()"></button>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg" id="importBtn">
                                    <i class="bi bi-upload me-2"></i>Import Data
                                </button>
                            </div>
                        </form>

                        <!-- Download Templates -->
                        <div class="mt-4 pt-4 border-top">
                            <h6 class="fw-bold mb-3"><i class="bi bi-file-earmark-arrow-down me-2"></i>Download Templates</h6>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('admin.csv.template', 'municipality_data') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-download me-1"></i>Municipality
                                </a>
                                <a href="{{ route('admin.csv.template', 'barangay_data') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-download me-1"></i>Barangay
                                </a>
                                <a href="{{ route('admin.csv.template', 'program_data') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-download me-1"></i>Program
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Export Section -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-download me-2"></i>Export CSV Data
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.csv.export') }}" method="POST" id="exportForm">
                            @csrf
                            
                            <!-- Data Type Selection -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Select Data Type</label>
                                <select name="export_type" class="form-select" required>
                                    <option value="">Choose data type...</option>
                                    <option value="municipality_data">Municipality Data</option>
                                    <option value="barangay_data">Barangay Data</option>
                                    <option value="program_data">Program Data</option>
                                </select>
                            </div>

                            <!-- Filters -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Filter by Year (Optional)</label>
                                <select name="year" class="form-select">
                                    <option value="">All Years</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Municipality Info -->
                            <div class="alert alert-info mb-4">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Municipality:</strong> {{ $adminMunicipality }}
                                <div class="small mt-1">Export will only include data for your municipality</div>
                            </div>

                            <!-- Export Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-warning btn-lg">
                                    <i class="bi bi-download me-2"></i>Export to CSV
                                </button>
                            </div>
                        </form>

                        <!-- Export Info -->
                        <div class="mt-4 pt-4 border-top">
                            <div class="icon-box download">
                                <i class="bi bi-info-circle"></i>
                            </div>
                            <h6 class="text-center fw-bold mb-2">Export Information</h6>
                            <ul class="small text-muted mb-0">
                                <li>Exports will include all filtered data</li>
                                <li>File format: CSV (Comma Separated Values)</li>
                                <li>Compatible with Excel and Google Sheets</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Import History -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2"></i>Recent Import History</span>
                <span class="badge bg-primary">{{ count($importLogs) }} Records</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover log-table mb-0">
                        <thead>
                            <tr>
                                <th>Date/Time</th>
                                <th>File Name</th>
                                <th>Type</th>
                                <th>Total Rows</th>
                                <th>Success</th>
                                <th>Failed</th>
                                <th>Status</th>
                                <th>User</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($importLogs as $log)
                            <tr>
                                <td>{{ $log->created_at->format('M d, Y H:i') }}</td>
                                <td><i class="bi bi-file-earmark-text me-1"></i>{{ $log->file_name }}</td>
                                <td><span class="badge bg-secondary">{{ str_replace('_', ' ', ucwords($log->file_type)) }}</span></td>
                                <td>{{ $log->total_rows }}</td>
                                <td><span class="text-success fw-bold">{{ $log->successful_rows }}</span></td>
                                <td><span class="text-danger fw-bold">{{ $log->failed_rows }}</span></td>
                                <td><span class="status-badge status-{{ $log->status }}">{{ $log->status }}</span></td>
                                <td>{{ $log->user->name ?? 'N/A' }}</td>
                                <td>
                                    @if($log->failed_rows > 0)
                                    <button class="btn btn-sm btn-outline-danger" onclick="viewErrors({{ $log->id }})">
                                        <i class="bi bi-exclamation-circle"></i> View Errors
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    No import history yet
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Details Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Import Errors</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="errorContent"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // File upload handling
        const uploadArea = document.getElementById('uploadArea');
        const csvFile = document.getElementById('csvFile');
        const selectedFile = document.getElementById('selectedFile');
        const importBtn = document.getElementById('importBtn');

        // Drag and drop
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                csvFile.files = files;
                displaySelectedFile(files[0]);
            }
        });

        // File input change
        csvFile.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                displaySelectedFile(e.target.files[0]);
            }
        });

        // Display selected file
        function displaySelectedFile(file) {
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('fileSize').textContent = formatFileSize(file.size);
            selectedFile.classList.remove('d-none');
        }

        // Clear file
        function clearFile() {
            csvFile.value = '';
            selectedFile.classList.add('d-none');
        }

        // Format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        // View errors
        function viewErrors(logId) {
            fetch(`/admin/csv/import-log/${logId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.errors.length > 0) {
                        let html = '<ul class="list-group">';
                        data.errors.forEach(error => {
                            html += `<li class="list-group-item list-group-item-danger">${error}</li>`;
                        });
                        html += '</ul>';
                        document.getElementById('errorContent').innerHTML = html;
                    } else {
                        document.getElementById('errorContent').innerHTML = '<p class="text-muted">No error details available.</p>';
                    }
                    new bootstrap.Modal(document.getElementById('errorModal')).show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load error details');
                });
        }

        // Form submission loading state
        document.getElementById('importForm').addEventListener('submit', function() {
            importBtn.disabled = true;
            importBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Importing...';
        });
    </script>
</body>
</html>
