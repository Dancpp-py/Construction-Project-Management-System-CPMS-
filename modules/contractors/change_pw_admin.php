<?php 
session_start();
include '../../includes/header.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: /CPMS/login_form.php");
    exit();
}
?>

<body class="body-form">
    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="card shadow custom-card">
            <div class="row">
                <div class="col-12 d-flex flex-column justify-content-center">
                    <div>
                        <a href="../../index.php">
                            <img src="../../assets/images/bg-logo.png" alt="logo" class="bg-logo d-block mx-auto mb-3">
                        </a>
                    </div>

                    <h3 class="h3 text-center custom-h3">Change Password</h3>
                    <input type="hidden" name="user_type" value="admin">

                    <hr class="border border-outline">

                    <form id="adminChangePWForm" method="POST">
                        <div class="mb-3">
                            <label for="password" class="form-label custom-label">Current Password</label>
                            <input type="password" id="password" minlength="8" name="current_admin_password" class="form-control form-control-sm custom-control">
                        </div>

                        <div class="mb-4">
                            <label for="confirm_password" class="form-label custom-label">New Password</label>
                            <input type="password" id="new_password" minlength="8" name="new_password" class="form-control form-control-sm custom-control">
                        </div>
                        
                        <div class="mb-4">
                            <label for="confirm_password" class="form-label custom-label">Confirm New Password</label>
                            <input type="password" id="confirm_password" minlength="8" name="confirm_new_password" class="form-control form-control-sm custom-control">
                        </div>

                       <div>
                            <button type="submit" class="btn text-white custom-btn w-100">
                                Accept Change
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php include '../../includes/footer.php'; ?>

