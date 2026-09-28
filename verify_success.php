<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Email Verified</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            color: #1f2937;
            background-color: #f5f7fa;
            font-family: Arial, sans-serif;
        }

        .card {
            width: 100%;
            border: none;
            padding: 2rem;
            max-width: 500px;
            border-radius: 12px;
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .success-icon {
            font-size: 60px;
            margin-bottom: 1rem;
        }

        .text-success-custom {
            color: #5b8fda !important;
        }

        .custom-btn {
            border: none;
            color: white;
            background-color: #3b6ba5;
        }

        .custom-btn:hover {
            border: none;
            color: white;
            background-color: #5b8fda;
        }

        .footer-text {
            color: #2c3a59 !important;
        }
    </style>
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card text-center">
            <div class="success-icon">✅</div>
            <h2 class="mb-3 text-success-custom">Email Verified Successfully!</h2>
            <p class="text-muted mb-5">
                Your email address has been verified. You can now log in to your account and start using our services.
            </p>
            <a href="login_form.php" class="btn custom-btn w-100 py-2">
                Go to Login
            </a>
            <small class="footer-text mt-2 mb-0">
                &copy; 2025 Construction Project Management System
            </small>
        </div>
    </div>
</body>
</html>