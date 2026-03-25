<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $programName }} Requirements - MSWDO</title>
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
            padding-bottom: 100px;
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-blue) 0%, #1a2a5c 100%);
            color: white;
            padding: 40px 0;
            margin-bottom: 40px;
        }
        
        .requirement-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-left: 4px solid #dee2e6;
            transition: all 0.3s ease;
        }
        
        .requirement-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .requirement-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
            font-size: 1rem;
        }
        
        .upload-area {
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .upload-area:hover {
            border-color: var(--primary-blue);
            background: #f8f9fa;
        }
        
        .file-preview {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            border: 1px solid #ddd;
        }
        
        .btn-submit-all {
            background: linear-gradient(135deg, var(--primary-blue) 0%, #1a2a5c 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 15px 40px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            box-shadow: 0 5px 20px rgba(44, 62, 143, 0.3);
        }
        
        .btn-submit-all:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(44, 62, 143, 0.4);
        }
        
        .btn-back {
            background: #6c757d;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            position: fixed;
            bottom: 30px;
            left: 30px;
            z-index: 1000;
        }
        
        .btn-back:hover {
            background: #5a6268;
            color: white;
        }
        
        .file-status {
            font-size: 0.85rem;
            margin-top: 5px;
        }
        
        .status-uploaded {
            color: #28a745;
        }
        
        .status-not-uploaded {
            color: #dc3545;
        }
        
        .selected-file {
            background: #e8f0fe;
            border-color: var(--primary-blue);
        }
    </style>
</head>
<body>
    <div class="page-header">
        <div class="container">
            <h1 class="mb-2">
                <i class="bi bi-file-text"></i> 
                {{ $programName }} - Required Documents
            </h1>
            <p class="mb-0">Please upload all the required documents for your application</p>
        </div>
    </div>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-list-check"></i> Required Documents for {{ $programName }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="requirements-container">
                            @foreach($requirements as $index => $req)
                                <div class="requirement-card" id="req-{{ $index }}">
                                    <div class="row align-items-center">
                                        <div class="col-md-5">
                                            <div class="requirement-name">{{ $req->requirement_name }}</div>
                                            <small class="text-muted">Required document</small>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="upload-area" onclick="document.getElementById('file-{{ $index }}').click()">
                                                <i class="bi bi-cloud-upload" style="font-size: 1.5rem;"></i>
                                                <p class="mb-0 small">Click to upload file</p>
                                                <small class="text-muted">JPG, PNG, PDF (Max 5MB)</small>
                                            </div>
                                            <input type="file" id="file-{{ $index }}" class="d-none" 
                                                   accept=".jpg,.jpeg,.png,.pdf" 
                                                   data-requirement="{{ $req->requirement_name }}"
                                                   data-index="{{ $index }}">
                                            <div id="file-info-{{ $index }}" class="file-status mt-2"></div>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <span id="status-icon-{{ $index }}" class="text-muted">
                                                <i class="bi bi-cloud-upload"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <a href="{{ route('user.programs') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Back to Programs
    </a>
    
    <button class="btn-submit-all" onclick="submitAllRequirements()">
        <i class="bi bi-send"></i> Submit All Documents
    </button>

    <script>
        // Store uploaded files temporarily
        let uploadedFiles = {};
        
        // Handle file selection
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function(e) {
                const index = this.dataset.index;
                const requirement = this.dataset.requirement;
                const file = this.files[0];
                
                if (file) {
                    // Validate file size (5MB max)
                    if (file.size > 5 * 1024 * 1024) {
                        alert('File size must be less than 5MB');
                        this.value = '';
                        return;
                    }
                    
                    // Validate file type
                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                    if (!allowedTypes.includes(file.type)) {
                        alert('Only JPG, PNG, and PDF files are allowed');
                        this.value = '';
                        return;
                    }
                    
                    // Store file
                    uploadedFiles[requirement] = {
                        file: file,
                        index: index,
                        name: file.name
                    };
                    
                    // Update UI
                    document.getElementById(`file-info-${index}`).innerHTML = `
                        <span class="text-success">
                            <i class="bi bi-check-circle"></i> ${file.name}
                        </span>
                    `;
                    document.getElementById(`status-icon-${index}`).innerHTML = `
                        <i class="bi bi-check-circle-fill" style="color: #28a745;"></i>
                    `;
                    document.getElementById(`req-${index}`).classList.add('selected-file');
                }
            });
        });
        
        // Submit all requirements
        function submitAllRequirements() {
            const requirements = Object.keys(uploadedFiles);
            
            if (requirements.length === 0) {
                alert('Please upload at least one document.');
                return;
            }
            
            // Create form data for all files
            const formData = new FormData();
            formData.append('program_type', '{{ $programType }}');
            
            for (const [requirement, data] of Object.entries(uploadedFiles)) {
                formData.append(`requirements[${requirement}]`, data.file);
                formData.append(`requirement_names[]`, requirement);
            }
            
            // Show loading
            const submitBtn = document.querySelector('.btn-submit-all');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Submitting...';
            submitBtn.disabled = true;
            
            // Submit to server
            fetch('{{ route('applications.upload-batch') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ ' + data.message);
                    window.location.href = '{{ route('user.my-requirements') }}';  // PALITAN ITO
                } else {
                    alert('❌ Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while submitting. Please try again.');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        }
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>