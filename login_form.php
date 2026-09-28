<?php 
session_start();
include 'includes/header.php';
?>

<body class="body-form">
    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="card shadow custom-card p-4">
            <div class="row">
                <div class="col-12 p-4 d-flex flex-column justify-content-center">
                    <div>
                        <a href="index.php">
                            <img src="assets/images/bg-logo.png" alt="logo" class="bg-logo d-block mx-auto mb-3">
                        </a>
                    </div>

                    <h3 class="h3 text-center custom-h3 mb-4">Login</h3>

                    <form id="swal-login" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label custom-label" >Email</label>
                            <input type="email" id="email" class="form-control form-control-sm custom-control" name="email" autocomplete="off">
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label custom-label">Password</label>
                            <input type="password" id="password" minlength="8" class="form-control form-control-sm custom-control" name="password">
                        </div>

                        <div class="d-flex flex-column">
                            <button type="submit" class="btn text-white custom-btn w-100 mb-2">
                                Login
                            </button>

                            <a href="forgot_pw.php" class="custom-a text-center text-decoration-none">Forgot Password?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php include 'includes/footer.php'; ?>