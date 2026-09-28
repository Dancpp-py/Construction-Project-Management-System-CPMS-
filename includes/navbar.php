<?php 
$current_page =basename($_SERVER['PHP_SELF']);
$landing_page = ($current_page == 'index.php');
$logged_in = (isset($_SESSION['admin_id']));
?>



<nav class="navbar navbar-expand-lg navbar-dark bg-dark my-auto shadow-sm top-0 position-fixed w-100 z-1 top-mobile-offset">
    <div class="container-fluid justify-content-end">#
        
        
        <ul class="navbar-nav flex-row gap-4 ms-auto align-items-center">
        

            <li class="nav-item">
                <a class="nav-link" href="/CPMS/index.php#hero-section">
                    <i class="bi bi-house-door-fill me-2"></i>Home
                </a>
            </li>
            
            <?php if (isset($_SESSION['admin_id']) || isset($_SESSION['contractor_id'])): ?>
                <li class="nav-item dropdown d-none d-lg-block">
                    <a class="nav-link dropdown-toggle" href="#" id="profileDropDown" role="button" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-2"></i>
                        
                        <?php
                            if(isset($_SESSION['admin_name'])) {
                                echo htmlspecialchars($_SESSION['admin_name']);
                            } else if (isset($_SESSION['contractor_name'])) {
                                echo htmlspecialchars($_SESSION['contractor_name']);
                            }           
                        ?>

                        <?php if (isset($_SESSION['user_type'])): ?>
                            <h6 class="badge text-bg-secondary mb-0" >
                                <?php echo ucfirst($_SESSION['user_type']); ?>
                            </h6>
                        <?php endif; ?>
                    </a>
                    
                    <ul class="dropdown-menu dropdown-menu-end text-center" aria-labelledby="profileDropDown">
                        <li><a class="dropdown-item swal-logout"><i class="bi bi-box-arrow-left"></i> Logout</a></li>
                    </ul>
                </li>

                <li class="nav-item d-lg-none">
                    <a class="nav-link">
                        <i class="bi bi-person-circle me-2"></i>
                        <?php
                            if(isset($_SESSION['admin_name'])) {
                                echo htmlspecialchars($_SESSION['admin_name']);
                            } else if (isset($_SESSION['contractor_name'])) {
                                echo htmlspecialchars($_SESSION['contractor_name']);
                            }
                        ?>
                    </a>
                </li>
                
                
                <li class="nav-item d-lg-none">
                    <a href="#" class="nav-link swal-logout">
                        <i class="bi bi-box-arrow-left me-2"></i> Logout
                    </a>
                </li>
                
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="#about-section">
                        <i class="bi bi-info-circle-fill me-2"></i>About Us
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-bold" href="/CPMS/login_form.php">
                        Login
                    </a>
                </li>
            <?php endif; ?>

             <?php if (!$landing_page && $logged_in): ?>
                <button class="btn btn-outline-light d-lg-none me-3" id="sidebarToggle" type="button">
                    <i class="bi bi-list fs-4"></i>
                </button>
            <?php endif; ?>
        </ul>

       
    </div>
</nav>
