<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MSWDO Analysis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .login-header h3 {
            font-weight: 700;
            margin-bottom: 10px;
        }
        .login-body {
            padding: 40px;
            background: white;
        }
        .btn-login {
            background: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(44, 62, 143, 0.3);
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
        .form-control {
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            padding: 10px 15px;
        }
        .form-control:focus {
            border-color: #2C3E8F;
            box-shadow: 0 0 0 3px rgba(44, 62, 143, 0.1);
        }
        .forgot-link {
            color: #2C3E8F;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .forgot-link:hover {
            color: #FDB913;
            text-decoration: underline;
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
            <div class="col-md-5">
                <div class="card login-card">
                    <div class="login-header">
                        <i class="bi bi-shield-lock" style="font-size: 3rem;"></i>
                        <h3>MSWDO Analysis System</h3>
                        <p class="mb-0">Login to your account</p>
                    </div>
                    <div class="login-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <p class="mb-0">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Username or Email</label>
                                <input type="text" name="login" class="form-control" value="{{ old('login') }}" required autofocus>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>

                            <button type="submit" class="btn btn-login text-white">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </button>
                        </form>

                        <!-- FORGOT PASSWORD LINK - ADD THIS -->
                        <div class="text-center mt-3">
                            <a href="{{ route('password.request') }}" class="forgot-link">
                                <i class="bi bi-key"></i> Forgot Password?
                            </a>
                        </div>

                        <div class="text-center mt-3">
                            <p class="mb-0">Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>