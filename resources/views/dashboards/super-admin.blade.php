@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Super Admin Dashboard</h1>
            <p>Welcome, {{ Auth::user()->full_name }}!</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3 stat-card-clickable" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#usersModal">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text display-6">{{ $totalUsers }}</p>
                    <small class="text-white-50">Click to view details</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3 stat-card-clickable" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#applicationsModal">
                <div class="card-body">
                    <h5 class="card-title">Total Applications</h5>
                    <p class="card-text display-6">{{ $totalApplications }}</p>
                    <small class="text-white-50">Click to view details</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3 stat-card-clickable" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#pendingModal">
                <div class="card-body">
                    <h5 class="card-title">Pending</h5>
                    <p class="card-text display-6">{{ $pendingApplications }}</p>
                    <small class="text-white-50">Click to view details</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3 stat-card-clickable" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#approvedModal">
                <div class="card-body">
                    <h5 class="card-title">Approved</h5>
                    <p class="card-text display-6">{{ $approvedApplications }}</p>
                    <small class="text-white-50">Click to view details</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Data -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Users</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentUsers as $user)
                            <tr>
                                <td>{{ $user->full_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->status == 'active' ? 'success' : 'danger' }}">
                                        {{ $user->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Applications</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Applicant</th>
                                <th>Program</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentApplications as $app)
                            <tr>
                                <td>{{ $app->full_name }}</td>
                                <td>{{ str_replace('_', ' ', $app->program_type) }}</td>
                                <td>
                                    <span class="badge bg-{{ $app->status == 'approved' ? 'success' : ($app->status == 'pending' ? 'warning' : 'danger') }}">
                                        {{ $app->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div

        </div>
    </div>
</div>

<!-- Users Modal -->
<div class="modal fade" id="usersModal" tabindex="-1" aria-labelledby="usersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="usersModalLabel">All Users ({{ $totalUsers }})</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Municipality</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allUsers as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->full_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td><span class="badge bg-secondary">{{ $user->role }}</span></td>
                                <td>{{ $user->municipality ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->status == 'active' ? 'success' : 'danger' }}">
                                        {{ $user->status }}
                                    </span>
                                </td>
                                <td>{{ $user->id }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="{{ route('superadmin.users') }}" class="btn btn-primary">Manage Users</a>
            </div>
        </div>
    </div>
</div>

<!-- All Applications Modal -->
<div class="modal fade" id="applicationsModal" tabindex="-1" aria-labelledby="applicationsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="applicationsModalLabel">All Applications ({{ $totalApplications }})</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Applicant</th>
                                <th>Program</th>
                                <th>Municipality</th>
                                <th>Status</th>
                                <th>Date Applied</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allApplications as $app)
                            <tr>
                                <td>{{ $app->id }}</td>
                                <td>{{ $app->full_name }}</td>
                                <td>{{ str_replace('_', ' ', $app->program_type) }}</td>
                                <td>{{ $app->municipality }}</td>
                                <td>
                                    <span class="badge bg-{{ $app->status == 'approved' ? 'success' : ($app->status == 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($app->status) }}
                                    </span>
                                </td>
                                <td>{{ optional($app->application_date)->format('M d, Y') ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Pending Applications Modal -->
<div class="modal fade" id="pendingModal" tabindex="-1" aria-labelledby="pendingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="pendingModalLabel">Pending Applications ({{ $pendingApplications }})</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Applicant</th>
                                <th>Program</th>
                                <th>Municipality</th>
                                <th>Date Applied</th>
                                <th>Days Pending</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingApplicationsList as $app)
                            <tr>
                                <td>{{ $app->id }}</td>
                                <td>{{ $app->full_name }}</td>
                                <td>{{ str_replace('_', ' ', $app->program_type) }}</td>
                                <td>{{ $app->municipality }}</td>
                                <td>{{ optional($app->application_date)->format('M d, Y') ?? 'N/A' }}</td>
                                <td>
                                    @if($app->application_date)
                                        {{ $app->application_date->diffInDays(now()) }} days
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Approved Applications Modal -->
<div class="modal fade" id="approvedModal" tabindex="-1" aria-labelledby="approvedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="approvedModalLabel">Approved Applications ({{ $approvedApplications }})</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Applicant</th>
                                <th>Program</th>
                                <th>Municipality</th>
                                <th>Date Applied</th>
                                <th>Date Approved</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($approvedApplicationsList as $app)
                            <tr>
                                <td>{{ $app->id }}</td>
                                <td>{{ $app->full_name }}</td>
                                <td>{{ str_replace('_', ' ', $app->program_type) }}</td>
                                <td>{{ $app->municipality }}</td>
                                <td>{{ optional($app->application_date)->format('M d, Y') ?? 'N/A' }}</td>
                                <td>{{ optional($app->application_date)->format('M d, Y') ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
.stat-card-clickable {
    transition: all 0.3s ease;
}

.stat-card-clickable:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.modal-xl {
    max-width: 1200px;
}

.table-responsive {
    max-height: 500px;
    overflow-y: auto;
}
</style>
@endsection
