<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - MSWDO Analysis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
        }
        .otp-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        .otp-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px;
            text-align: center;
        }
        .otp-body {
            padding: 40px;
        }
        .otp-input {
            font-size: 24px;
            text-align: center;
            letter-spacing: 8px;
            font-weight: bold;
        }
        .btn-verify {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 25px;
            font-weight: bold;
        }
        .timer {
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card otp-card">
                    <div class="otp-header">
                        <h3>Email Verification</h3>
                        <p class="mb-0">Enter the OTP sent to your email</p>
                    </div>
                    <div class="otp-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <p class="mb-0">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('otp.verify') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="otp" class="form-label">6-Digit OTP Code</label>
                                <input type="text" class="form-control otp-input" id="otp" name="otp" 
                                       maxlength="6" pattern="\d{6}" required autofocus>
                            </div>

                            <button type="submit" class="btn btn-verify w-100">Verify Email</button>
                        </form>

                        <div class="text-center mt-3">
                            <p class="timer">Didn't receive OTP? </p>
                            <form method="POST" action="{{ route('otp.resend') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link p-0">Resend OTP</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>