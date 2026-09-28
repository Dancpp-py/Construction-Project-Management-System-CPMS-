<?php 
session_start();
include '../../includes/header.php';
?>

<body class="body-register-form">
    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="card shadow-lg contractor-card">
            <div class="row">
                <div class="col-12 d-flex flex-column justify-content-center">
                    <div>
                        <a href="../../index.php">
                            <img src="../../assets/images/bg-logo.png" alt="logo" class="bg-logo d-block mx-auto mb-3">
                        </a>
                    </div>

                    <h3 class="h3 text-center contractor-h3 mb-4">Add Contractor</h3>

                    <input type="hidden" name="user_type" value="contractor">

                    <form id="contractorRegisterForm" method="POST">
                        <div class="mb-3">
                            <label for="full_name" class="form-label contractor-label">Contractor Name</label>
                            <input type="text" id="full_name" name="contractor_name"class="form-control form-control-sm custom-control" autocomplete="off">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label contractor-label">Contractor Email Address</label>
                            <input type="email" id="email" name="contractor_email" class="form-control form-control-sm custom-control" autocomplete="off">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label contractor-label">Password</label>
                            <input type="password" id="password" minlength="8" name="contractor_password" class="form-control form-control-sm custom-control">
                        </div>

                        <div class="mb-4">
                            <label for="confirm_password" class="form-label contractor-label">Confirm Password</label>
                            <input type="password" id="confirm_password" minlength="8" name="confirm_password" class="form-control form-control-sm custom-control">
                        </div>

                        <div>
                            <button type="submit" class="btn text-white contractor-btn w-100">
                                Add
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php include '../../includes/footer.php'; ?>

