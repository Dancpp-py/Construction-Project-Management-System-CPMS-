<?php include 'includes/header.php'; ?>

<body class="fp-body">
    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
       <div class="card forgot-pw-card text-white shadow-lg p-4 border-0">
            <div class="card-body d-flex flex-column justify-content-center">
                <div class="text-center card-title fp-card-title">
                    <i class="bi bi-key-fill fs-1"></i>
                    <h4 class="">Forgot Password?</h4>
                    <p class="fp-card-text">Enter your email address to reset your password.</p>
                </div>
                
                <hr class="border custom-outline">
                
                <form id="forgotPasswordForm" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-pw-label mb-2">Email Address</label>
                        <input type="email" class="form-control form-control-sm custom-control" name="admin_email" autocomplete="off">
                    </div>

                    <div class="d-flex flex-column">
                        <button type="submit" class="btn forgot-pw-btn w-100 text-white mt-3 mb-2">Send Reset Link</button>
                        
                        <a href="/CPMS/login_form.php" class="text-decoration-none text-center custom-a">Back to Login</a>
                    </div>
                </form>
            </div>
       </div>   
    </div>

<?php include 'includes/footer.php'; ?>

