<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Solo Parent Master Requirements - MSWDO Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2C3E8F;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --secondary-yellow: #FDB913;
            --bg-light: #F8FAFC;
            --border-light: #E2E8F0;
        }

        body {
            background: var(--bg-light);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            color: #1e293b;
        }

        /* Hero */
        .page-hero {
            background: var(--primary-gradient);
            border-radius: 18px;
            padding: 28px 32px;
            color: white;
            box-shadow: 0 4px 20px rgba(44, 62, 143, 0.15);
        }

        .page-hero h1 {
            font-size: 1.55rem;
            font-weight: 800;
            margin: 0 0 6px;
        }

        .page-hero p {
            margin: 0;
            opacity: 0.88;
            font-size: 0.92rem;
        }

        /* Category Nav Pills */
        .category-nav-wrapper {
            background: white;
            border-radius: 16px;
            border: 1px solid var(--border-light);
            padding: 12px;
            margin-bottom: 22px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            overflow-x: auto;
            white-space: nowrap;
        }

        .cat-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 0.84rem;
            font-weight: 700;
            color: #475569;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-right: 6px;
            border: 1px solid transparent;
        }

        .cat-pill:hover {
            background: #f1f5f9;
            color: var(--primary-blue);
        }

        .cat-pill.active {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 3px 12px rgba(44, 62, 143, 0.25);
        }

        .cat-pill .badge {
            font-size: 0.72rem;
            font-weight: 800;
            padding: 3px 7px;
            border-radius: 10px;
        }

        .cat-pill.active .badge {
            background: var(--secondary-yellow) !important;
            color: var(--primary-blue) !important;
        }

        /* Cards */
        .info-card {
            background: white;
            border-radius: 16px;
            border: 1px solid var(--border-light);
            padding: 24px 28px;
            box-shadow: 0 2px 12px rgba(0,0,0,.04);
            margin-bottom: 22px;
        }

        /* Table */
        .table thead th {
            background: #f1f5f9;
            color: var(--primary-blue);
            font-weight: 700;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: none;
            padding: 12px 16px;
        }

        .table tbody td {
            padding: 14px 16px;
            font-size: 0.88rem;
            vertical-align: middle;
            border-color: #f1f5f9;
        }

        .badge-slot {
            font-size: 0.74rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 12px;
        }

        .badge-mandatory {
            background: #e2e8f0;
            color: #334155;
        }

        .badge-or-group {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }

        .badge-active {
            background: #d4edda;
            color: #155724;
        }

        .badge-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        /* Modals */
        .modal-header {
            background: var(--primary-gradient);
            color: white;
            border-radius: 14px 14px 0 0;
            padding: 18px 24px;
        }

        .modal-title {
            font-weight: 800;
            font-size: 1.05rem;
        }

        .modal-content {
            border-radius: 14px;
            border: none;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>
    @include('components.admin-navbar', ['active' => 'applications'])

    <div class="container mt-4 pb-5">
        @include('components.admin-notification')

        {{-- Hero Header --}}
        <div class="page-hero mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1>Solo Parent Requirements Management</h1>
                    <p>Configure master documentary requirements and alternative (OR) groups for Categories A1 &ndash; F.</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.requirements') }}" class="btn btn-light btn-sm fw-bold px-3 py-2" style="border-radius:30px;">
                        ← Back to Applications
                    </a>
                    <button type="button" class="btn btn-warning btn-sm fw-bold px-3 py-2 text-dark" style="border-radius:30px;background:var(--secondary-yellow);border:none;" onclick="openAddModal()">
                        + Add Requirement
                    </button>
                    <button type="button" class="btn btn-outline-light btn-sm fw-bold px-3 py-2" style="border-radius:30px;" onclick="openResetModal()">
                        ↻ Reset Defaults
                    </button>
                </div>
            </div>
        </div>

        {{-- Snapshot Notice Alert --}}
        <div style="background:#eef2ff;border:1px solid #c7d2fe;border-radius:14px;padding:16px 20px;margin-bottom:20px;display:flex;align-items:flex-start;gap:14px;">
            <span style="font-size:1.3rem;">ℹ️</span>
            <div>
                <div style="font-weight:800;color:var(--primary-blue);font-size:.92rem;margin-bottom:2px;">
                    Statutory Snapshot Architecture Notice
                </div>
                <div style="font-size:.85rem;color:#334155;line-height:1.55;">
                    Master requirements configured here are automatically snapshotted to new applicants when their appointment interview is validated. 
                    <strong>Existing applicants retain their frozen requirement snapshots</strong> so ongoing evaluations are never disrupted by future requirement updates.
                </div>
            </div>
        </div>

        {{-- Category Selection Tabs --}}
        <div class="category-nav-wrapper">
            @foreach($categories as $code => $cat)
                @php $cnt = $categoryCounts[$code] ?? ['total' => 0, 'active' => 0]; @endphp
                <a href="{{ route('admin.solo-parent-requirements.index', ['category' => $code]) }}" 
                   class="cat-pill {{ $selectedCategory === $code ? 'active' : '' }}">
                    <span>Category {{ $code }}</span>
                    <span class="badge bg-light text-dark">{{ $cnt['active'] }}</span>
                </a>
            @endforeach
        </div>

        {{-- Selected Category Information Header Card --}}
        <div class="info-card mb-4" style="border-left: 5px solid var(--primary-blue);">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                <div>
                    <span class="badge" style="background:var(--primary-blue);font-size:.85rem;font-weight:800;padding:6px 12px;border-radius:8px;">
                        Category {{ $selectedCategory }}
                    </span>
                    <span class="ms-2 fw-bold text-dark" style="font-size:1.1rem;">
                        {{ $currentCategory['title'] }}
                    </span>
                </div>
                <div>
                    <span class="badge bg-light text-dark border px-3 py-2" style="font-size:.82rem;border-radius:20px;">
                        Total Active Requirements: {{ $requirements->where('is_active', true)->count() }}
                    </span>
                </div>
            </div>
            <div class="text-muted small mt-2" style="line-height:1.55;">
                {{ $currentCategory['description'] }}
            </div>
        </div>

        {{-- Requirements Table --}}
        <div class="info-card p-0 overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th style="width: 70px;" class="text-center">Order</th>
                            <th>Requirement Document Name</th>
                            <th>Slot / Group Title</th>
                            <th style="width: 150px;" class="text-center">Slot Type</th>
                            <th style="width: 110px;" class="text-center">Status</th>
                            <th>Guidelines / Notes</th>
                            <th style="width: 160px;" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requirements as $req)
                        <tr class="{{ !$req->is_active ? 'opacity-50' : '' }}">
                            <td class="text-center fw-bold text-muted">{{ $req->sort_order }}</td>
                            <td>
                                <div class="fw-bold text-dark" style="font-size:.92rem;">
                                    {{ $req->requirement_name }}
                                </div>

                            </td>
                            <td>
                                <div class="fw-semibold text-secondary" style="font-size:.86rem;">
                                    {{ $req->group_title }}
                                </div>
                            </td>
                            <td class="text-center">
                                @if($req->is_or_group)
                                    <span class="badge badge-slot badge-or-group">Alternative (OR)</span>
                                @else
                                    <span class="badge badge-slot badge-mandatory">Mandatory Slot</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($req->is_active)
                                    <span class="badge badge-slot badge-active">Active</span>
                                @else
                                    <span class="badge badge-slot badge-inactive">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-muted small" style="max-width:320px;line-height:1.4;">
                                    {{ $req->description ?: '—' }}
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary fw-bold" 
                                            onclick="openEditModal({{ json_encode($req) }})" style="font-size:.78rem;border-radius:8px;">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.solo-parent-requirements.toggle', $req->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $req->is_active ? 'btn-outline-danger' : 'btn-outline-success' }} fw-bold" style="font-size:.78rem;border-radius:8px;">
                                            {{ $req->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <div style="font-size:2.5rem;margin-bottom:8px;">📂</div>
                                <div class="fw-bold">No master requirements found for Category {{ $selectedCategory }}</div>
                                <div class="small mt-1">Click "+ Add Requirement" or "Reset Defaults" above to seed statutory requirements.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Code 1,2 Benefit Track (Displayed Separately from Eligibility Categories A1-F) --}}
        @if(isset($benefitRequirements) && $benefitRequirements->isNotEmpty())
        <div class="info-card mb-4" style="border-left: 5px solid #0284c7; background: #f8fafc;">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <div>
                    <span class="badge" style="background:#0284c7;font-size:.85rem;font-weight:800;padding:6px 12px;border-radius:8px;">
                        Benefit Track &bull; Code 1,2
                    </span>
                    <span class="ms-2 fw-bold text-dark" style="font-size:1.1rem;">
                        Solo Parent Availing Subsidy &amp; Discount
                    </span>
                </div>
                <div>
                    <span class="badge bg-light text-dark border px-3 py-2" style="font-size:.82rem;border-radius:20px;">
                        Benefit Requirements: {{ $benefitRequirements->count() }} (Separate Track)
                    </span>
                </div>
            </div>
            <div class="text-muted small mb-3" style="line-height:1.55;">
                These statutory documentary requirements apply exclusively when an applicant avails of the optional <strong>Solo Parent Subsidy &amp; Discount (Code 1,2)</strong> track. They are snapshotted in addition to their assigned primary category (A1&ndash;F) requirements.
            </div>
            <div class="table-responsive bg-white rounded border overflow-hidden">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th style="width: 70px;" class="text-center">Order</th>
                            <th>Requirement Document Name</th>
                            <th>Slot / Group Title</th>
                            <th style="width: 170px;" class="text-center">Track Type</th>
                            <th style="width: 110px;" class="text-center">Status</th>
                            <th>Guidelines / Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($benefitRequirements as $bReq)
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $bReq->order_num }}</td>
                            <td>
                                <div class="fw-bold text-dark" style="font-size:.92rem;">{{ $bReq->requirement_name }}</div>

                            </td>
                            <td>
                                <div class="fw-semibold text-secondary" style="font-size:.86rem;">{{ $bReq->group_title }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-slot" style="background:#e0f2fe;color:#0369a1;border:1px solid #bae6fd;">
                                    Subsidy &amp; Discount
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-slot badge-active">Active</span>
                            </td>
                            <td>
                                <div class="text-muted small" style="max-width:320px;line-height:1.4;">
                                    {{ $bReq->description ?: '—' }}
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    {{-- ADD REQUIREMENT MODAL --}}
    <div class="modal fade" id="addRequirementModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.solo-parent-requirements.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">+ Add Requirement to Category</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-muted text-uppercase">Target Category</label>
                                <select name="category_code" class="form-select" required>
                                    @foreach($categories as $code => $cat)
                                        <option value="{{ $code }}" {{ $selectedCategory === $code ? 'selected' : '' }}>
                                            Category {{ $code }} ({{ Str::limit($cat['title'], 28) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-bold small text-muted text-uppercase">Requirement Document Name</label>
                                <input type="text" name="requirement_name" class="form-control" required placeholder="e.g. PSA Death Certificate of Spouse">
                            </div>
                            <div class="col-md-9">
                                <label class="form-label fw-bold small text-muted text-uppercase">Group / Slot Title</label>
                                <input type="text" name="group_title" class="form-control" placeholder="e.g. Proof of Spouse Decease (OR Alternative)">
                                <div class="form-text small">Documents with the same Slot/Group Title belong to the same required slot.</div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control" value="10">
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch p-3 bg-light rounded border">
                                    <input class="form-check-input ms-0 me-3" type="checkbox" name="is_or_group" value="1" id="addIsOrGroup">
                                    <label class="form-check-label fw-bold" for="addIsOrGroup">
                                        Is this an Alternative (OR Group) option?
                                    </label>
                                    <div class="text-muted small ms-1">
                                        When checked, the applicant only needs to submit ANY ONE approved option under this group to satisfy the requirement slot.
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted text-uppercase">Notes & Verification Guidelines for Admin / Applicant</label>
                                <textarea name="description" rows="2" class="form-control" placeholder="Specific guidelines or instructions for this document..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light px-4 py-3">
                        <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary fw-bold px-4" style="background:var(--primary-blue);border:none;">
                            Save Requirement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- EDIT REQUIREMENT MODAL --}}
    <div class="modal fade" id="editRequirementModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="editRequirementForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Master Requirement</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-muted text-uppercase">Category</label>
                                <select name="category_code" id="editCategoryCode" class="form-select" required>
                                    @foreach($categories as $code => $cat)
                                        <option value="{{ $code }}">Category {{ $code }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-bold small text-muted text-uppercase">Requirement Document Name</label>
                                <input type="text" name="requirement_name" id="editRequirementName" class="form-control" required>
                            </div>
                            <div class="col-md-9">
                                <label class="form-label fw-bold small text-muted text-uppercase">Group / Slot Title</label>
                                <input type="text" name="group_title" id="editGroupTitle" class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Sort Order</label>
                                <input type="number" name="sort_order" id="editSortOrder" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch p-3 bg-light rounded border h-100">
                                    <input class="form-check-input ms-0 me-3" type="checkbox" name="is_or_group" value="1" id="editIsOrGroup">
                                    <label class="form-check-label fw-bold" for="editIsOrGroup">
                                        Is Alternative (OR Group)
                                    </label>
                                    <div class="text-muted small">Applicant submits any 1 option in this group.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch p-3 bg-light rounded border h-100">
                                    <input class="form-check-input ms-0 me-3" type="checkbox" name="is_active" value="1" id="editIsActive">
                                    <label class="form-check-label fw-bold" for="editIsActive">
                                        Requirement is Active
                                    </label>
                                    <div class="text-muted small">Inactive requirements will not be added to new applicants.</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted text-uppercase">Notes & Verification Guidelines</label>
                                <textarea name="description" id="editDescription" rows="2" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light px-4 py-3">
                        <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary fw-bold px-4" style="background:var(--primary-blue);border:none;">
                            Update Requirement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- RESET DEFAULTS MODAL --}}
    <div class="modal fade" id="resetDefaultsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.solo-parent-requirements.reset-defaults') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title text-white">↻ Restore Statutory Defaults</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="text-secondary" style="line-height:1.55;">
                            This action resets master requirement definitions back to the statutory defaults defined in the Philippine Solo Parents Welfare Act.
                        </p>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Scope of Reset</label>
                            <select name="category_code" class="form-select">
                                <option value="{{ $selectedCategory }}">Reset Category {{ $selectedCategory }} Only</option>
                                <option value="">Reset All Categories (A1 through F)</option>
                            </select>
                        </div>
                        <div class="alert alert-warning small mb-0">
                            <strong>Note:</strong> Already validated applicants will continue to preserve their individual snapshot records.
                        </div>
                    </div>
                    <div class="modal-footer bg-light px-4 py-3">
                        <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger fw-bold px-4">
                            Proceed & Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openAddModal() {
            var modal = new bootstrap.Modal(document.getElementById('addRequirementModal'));
            modal.show();
        }

        function openResetModal() {
            var modal = new bootstrap.Modal(document.getElementById('resetDefaultsModal'));
            modal.show();
        }

        function openEditModal(req) {
            document.getElementById('editRequirementForm').action = "/admin/solo-parent-requirements/" + req.id + "/update";
            document.getElementById('editCategoryCode').value = req.category_code;
            document.getElementById('editRequirementName').value = req.requirement_name;
            document.getElementById('editGroupTitle').value = req.group_title || '';
            document.getElementById('editSortOrder').value = req.sort_order || 10;
            document.getElementById('editIsOrGroup').checked = Boolean(req.is_or_group);
            document.getElementById('editIsActive').checked = Boolean(req.is_active);
            document.getElementById('editDescription').value = req.description || '';

            var modal = new bootstrap.Modal(document.getElementById('editRequirementModal'));
            modal.show();
        }
    </script>
</body>
</html>
