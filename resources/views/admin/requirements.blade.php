<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requirements - {{ $municipality }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Requirements Management</h1>
            <div>
                <span class="badge bg-primary p-2">Municipality: {{ $municipality }}</span>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary ms-2">Back to Dashboard</a>
            </div>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        @if(isset($fileMonitorings) && $fileMonitorings->count() > 0)
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Submitted Requirements</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Applicant</th>
                                    <th>Program</th>
                                    <th>Submitted Date</th>
                                    <th>Status</th>
                                    <th>Files</th>
                                    <th>Actions</th>
                                </thead>
                            <tbody>
                                @foreach($fileMonitorings as $fm)
                                <tr>
                                    <td>{{ $fm->id }}</td>
                                    <td>
                                        <strong>{{ $fm->application->full_name ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $fm->application->barangay ?? 'No barangay' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ str_replace('_', ' ', $fm->application->program_type ?? 'N/A') }}</span>
                                    </td>
                                    <td>{{ $fm->created_at ? $fm->created_at->format('M d, Y h:i A') : 'N/A' }}</td>
                                    <td>
                                        @php
                                            $statusClass = $fm->overall_status == 'approved' ? 'success' : ($fm->overall_status == 'rejected' ? 'danger' : ($fm->overall_status == 'in_review' ? 'warning' : 'secondary'));
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $fm->overall_status ?? 'pending')) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $totalFiles = $fm->fileUploads->count();
                                            $uploadedFiles = $fm->fileUploads->where('file_path', '!=', null)->count();
                                        @endphp
                                        <span class="badge bg-secondary">{{ $uploadedFiles }}/{{ $totalFiles }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.view-requirement', $fm->id) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> View Details
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $fileMonitorings->links() }}
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> No requirements submitted yet for {{ $municipality }}.
            </div>
        @endif
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>