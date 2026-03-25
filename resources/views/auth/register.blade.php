<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - MSWDO Analysis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 0;
        }
        .register-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px;
            text-align: center;
        }
        .register-body {
            padding: 40px;
        }
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 25px;
            font-weight: bold;
            transition: transform 0.2s;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            color: white;
        }
        .back-link {
            color: white;
            text-decoration: none;
            opacity: 0.9;
        }
        .back-link:hover {
            color: white;
            opacity: 1;
        }
        .dev-warning {
            background-color: #ffc107;
            color: #000;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .form-label {
            font-weight: 600;
            color: #2C3E8F;
            margin-bottom: 8px;
        }
        .text-muted {
            font-size: 0.85rem;
            margin-top: 5px;
        }
        .age-info {
            background: #f8f9fa;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 15px;
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .age-value {
            font-weight: 700;
            color: #2C3E8F;
            font-size: 1rem;
        }
        .age-badge {
            background: #2C3E8F;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .age-badge.child {
            background: #28a745;
        }
        .age-badge.teen {
            background: #ffc107;
            color: #333;
        }
        .age-badge.adult {
            background: #17a2b8;
        }
        .age-badge.senior {
            background: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-center mb-4">
            <a href="/analysis" class="back-link">
                <i class="bi bi-arrow-left"></i> Back to Analysis
            </a>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card register-card">
                    <div class="register-header">
                        <h3>MSWDO Analysis System</h3>
                        <p class="mb-0">Create your account</p>
                    </div>
                    <div class="register-body">
                        <!-- TEMPORARY DEVELOPMENT WARNING -->
                        <div class="dev-warning text-center">
                            ⚠️ DEVELOPMENT MODE - Role selection enabled ⚠️
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}" id="registerForm">
                            @csrf
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control @error('full_name') is-invalid @enderror" 
                                       id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                       id="username" name="username" value="{{ old('username') }}" required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- BIRTHDATE FIELD -->
                            <div class="mb-3">
                                <label for="birthdate" class="form-label">Birth Date</label>
                                <input type="date" class="form-control @error('birthdate') is-invalid @enderror" 
                                       id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required
                                       onchange="calculateAge()" oninput="calculateAge()">
                                <small class="text-muted">Please enter your birth date</small>
                                @error('birthdate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="age-display" class="mt-2"></div>
                            </div>

                            <!-- TEMPORARY ROLE SELECTION -->
                            <div class="mb-3">
                                <label for="role" class="form-label">Account Type (Temporary)</label>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" name="role" required>
                                    @foreach($roles as $value => $label)
                                        <option value="{{ $value }}" {{ old('role') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">⚠️ This will be removed in production</small>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- MUNICIPALITY FIELD -->
                            <div class="mb-3">
                                <label for="municipality" class="form-label">Municipality</label>
                                <select class="form-select @error('municipality') is-invalid @enderror" 
                                        id="municipality" name="municipality" required>
                                    <option value="">Select Municipality</option>
                                    @foreach($municipalities as $municipality)
                                        <option value="{{ $municipality->name }}" 
                                            {{ old('municipality') == $municipality->name ? 'selected' : '' }}>
                                            {{ $municipality->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Select your municipality of residence</small>
                                @error('municipality')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                <small class="text-muted">Minimum 8 characters</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>

                            <button type="submit" class="btn btn-register w-100">Register</button>
                        </form>

                        <div class="text-center mt-3">
                            <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function calculateAge() {
            const birthdate = document.getElementById('birthdate').value;
            const ageDisplay = document.getElementById('age-display');
            
            if (birthdate) {
                const today = new Date();
                const birth = new Date(birthdate);
                
                // Calculate age
                let age = today.getFullYear() - birth.getFullYear();
                const monthDiff = today.getMonth() - birth.getMonth();
                const dayDiff = today.getDate() - birth.getDate();
                
                if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
                    age--;
                }
                
                // Check if birth date is valid and not in the future
                if (birth > today) {
                    ageDisplay.innerHTML = `
                        <div class="alert alert-danger py-2 mb-0">
                            <i class="bi bi-exclamation-triangle"></i> 
                            <strong>Invalid:</strong> Birth date cannot be in the future.
                        </div>
                    `;
                    document.getElementById('birthdate').classList.add('is-invalid');
                } else if (age < 0) {
                    ageDisplay.innerHTML = `
                        <div class="alert alert-danger py-2 mb-0">
                            <i class="bi bi-exclamation-triangle"></i> 
                            <strong>Invalid:</strong> Please enter a valid birth date.
                        </div>
                    `;
                    document.getElementById('birthdate').classList.add('is-invalid');
                } else {
                    document.getElementById('birthdate').classList.remove('is-invalid');
                    
                    // Determine age group
                    let ageGroup = '';
                    let ageClass = '';
                    if (age >= 60) {
                        ageGroup = 'Senior Citizen';
                        ageClass = 'senior';
                    } else if (age >= 18) {
                        ageGroup = 'Adult';
                        ageClass = 'adult';
                    } else if (age >= 13) {
                        ageGroup = 'Teenager';
                        ageClass = 'teen';
                    } else if (age >= 0) {
                        ageGroup = 'Child';
                        ageClass = 'child';
                    }
                    
                    ageDisplay.innerHTML = `
                        <div class="age-info">
                            <span>
                                <i class="bi bi-cake2 me-2" style="color: #2C3E8F;"></i>
                                <span class="age-value">${age} years old</span>
                            </span>
                            <span class="age-badge ${ageClass}">${ageGroup}</span>
                        </div>
                    `;
                }
            } else {
                ageDisplay.innerHTML = '';
                document.getElementById('birthdate').classList.remove('is-invalid');
            }
        }
        
        // Trigger calculation on page load if there's an existing value
        document.addEventListener('DOMContentLoaded', function() {
            const birthdate = document.getElementById('birthdate').value;
            if (birthdate) {
                calculateAge();
            }
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>