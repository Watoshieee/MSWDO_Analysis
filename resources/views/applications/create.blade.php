<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Application - MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2C3E8F;
            --primary-blue-light: #E5EEFF;
            --primary-blue-soft: #5D7BB9;
            --secondary-yellow: #FDB913;
            --secondary-yellow-light: #FFF3D6;
            --accent-red: #C41E24;
            --accent-red-light: #FCE8E8;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --bg-light: #F8FAFC;
            --bg-white: #FFFFFF;
            --border-light: #E2E8F0;
        }

        body {
            background: var(--bg-light);
            font-family: 'Inter', 'Segoe UI', sans-serif;
            padding-bottom: 30px;
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
            color: white !important;
            font-weight: 500;
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

        .form-card {
            background: var(--bg-white);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            border: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
        }

        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid var(--border-light);
            padding: 10px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-blue-soft);
            box-shadow: 0 0 0 3px rgba(44, 62, 143, 0.1);
        }

        .btn-submit {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(44, 62, 143, 0.3);
            color: white;
        }

        .btn-cancel {
            background: var(--bg-light);
            color: var(--text-dark);
            border: 2px solid var(--border-light);
            border-radius: 12px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-cancel:hover {
            background: var(--accent-red-light);
            color: var(--accent-red);
            border-color: var(--accent-red);
        }

        .required-field:after {
            content: " *";
            color: var(--accent-red);
            font-weight: bold;
        }

        .info-text {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/analysis">
                <i class="bi bi-heart-fill"></i> MSWDO Analysis
            </a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="page-header">
            <h1 class="page-title">
                <i class="bi bi-plus-circle" style="color: var(--secondary-yellow);"></i>
                Create New Application
            </h1>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mt-2 mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-card">
            <form method="POST" action="{{ route('applications.store') }}">
                @csrf

                <div class="row">
                    <!-- Personal Information -->
                    <div class="col-12 mb-4">
                        <h5 style="color: var(--primary-blue);">
                            <i class="bi bi-person" style="color: var(--secondary-yellow);"></i>
                            Personal Information
                        </h5>
                        <hr>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label required-field">Full Name</label>
                        <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" 
                               value="{{ old('full_name') }}" required placeholder="Enter complete name">
                        <div class="info-text">e.g., Juan Dela Cruz</div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label required-field">Age</label>
                        <input type="number" name="age" class="form-control @error('age') is-invalid @enderror" 
                               value="{{ old('age') }}" required min="0" max="120" placeholder="Age">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label required-field">Gender</label>
                        <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                            <option value="">Select Gender</option>
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact_number" class="form-control @error('contact_number') is-invalid @enderror" 
                               value="{{ old('contact_number') }}" placeholder="e.g., 09123456789">
                        <div class="info-text">Optional but recommended</div>
                    </div>

                    <!-- Program Details -->
                    <div class="col-12 mb-4 mt-3">
                        <h5 style="color: var(--primary-blue);">
                            <i class="bi bi-heart" style="color: var(--secondary-yellow);"></i>
                            Program Details
                        </h5>
                        <hr>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label required-field">Program Type</label>
                        <select name="program_type" class="form-select @error('program_type') is-invalid @enderror" required>
                            <option value="">Select Program</option>
                            @foreach($programTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('program_type') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label required-field">Year</label>
                        <select name="year" class="form-select @error('year') is-invalid @enderror" required>
                            <option value="">Select Year</option>
                            @foreach($years as $value => $label)
                                <option value="{{ $value }}" {{ old('year') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <div class="info-text">Program implementation year</div>
                    </div>

                    <!-- Location Details -->
                    <div class="col-12 mb-4 mt-3">
                        <h5 style="color: var(--primary-blue);">
                            <i class="bi bi-geo-alt" style="color: var(--secondary-yellow);"></i>
                            Location Details
                        </h5>
                        <hr>
                    </div>

                    @if(isset($municipalities) && count($municipalities) > 0)
                    <div class="col-md-6 mb-3">
                        <label class="form-label required-field">Municipality</label>
                        <select name="municipality" class="form-select @error('municipality') is-invalid @enderror" 
                                id="municipality" required>
                            <option value="">Select Municipality</option>
                            @foreach($municipalities as $value => $label)
                                <option value="{{ $value }}" {{ old('municipality') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @else
                        <input type="hidden" name="municipality" value="{{ $municipality }}">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Municipality</label>
                            <input type="text" class="form-control" value="{{ $municipality }}" readonly disabled>
                            <div class="info-text">Auto-assigned based on your account</div>
                        </div>
                    @endif

                    <div class="col-md-6 mb-3">
                        <label class="form-label required-field">Barangay</label>
                        <select name="barangay" class="form-select @error('barangay') is-invalid @enderror" id="barangay" required>
                            <option value="">Select Barangay</option>
                            @foreach($barangays as $value => $label)
                                <option value="{{ $value }}" {{ old('barangay') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="col-12 mt-4">
                        <hr>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('applications.index') }}" class="btn-cancel">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                            <button type="submit" class="btn-submit">
                                <i class="bi bi-check-circle"></i> Create Application
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Dynamic Barangay Filter based on Municipality
        document.addEventListener('DOMContentLoaded', function() {
            const municipalitySelect = document.getElementById('municipality');
            const barangaySelect = document.getElementById('barangay');
            
            if (municipalitySelect) {
                municipalitySelect.addEventListener('change', function() {
                    const selectedMunicipality = this.value;
                    
                    // Clear current options
                    barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                    
                    if (selectedMunicipality) {
                        // Get barangays for selected municipality
                        fetch(`/api/barangays/${selectedMunicipality}`)
                            .then(response => response.json())
                            .then(data => {
                                data.forEach(barangay => {
                                    const option = document.createElement('option');
                                    option.value = barangay.name;
                                    option.textContent = barangay.name;
                                    barangaySelect.appendChild(option);
                                });
                            })
                            .catch(error => {
                                console.error('Error fetching barangays:', error);
                                // Fallback to all barangays if API fails
                                @foreach($barangays as $value => $label)
                                    if ('{{ $value }}'.includes(selectedMunicipality)) {
                                        const option = document.createElement('option');
                                        option.value = '{{ $value }}';
                                        option.textContent = '{{ $label }}';
                                        barangaySelect.appendChild(option);
                                    }
                                @endforeach
                            });
                    }
                });
            }
        });
    </script>
</body>
</html>