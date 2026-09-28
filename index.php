<?php
session_start();

include 'includes/header.php';
include 'includes/navbar.php';

if (isset($_SESSION['admin_id'])) {
    $href = '/CPMS/modules/admin/admin_dashboard.php';
} else if (isset($_SESSION['contractor_id'])){
    $href = '/CPMS/modules/contractors/contractor_dashboard.php';
} else {
    $href = '/CPMS/login_form.php';
}
?>
    <!-- Hero Side -->
    <section class="hero d-flex align-items-center text-white" id="hero-section">
        <div class="container-fluid hero-content">
            <h2>Building with Transparency, Accountability, and Trust.</h2>
            <p class="lead"> Construction Project Management System is a national platform that promotes transparency and accountability in every public construction project. It gives citizens real-time access to project progress, budgets, and every cent spent — building trust through open governance.</p>
            <a href="<?php echo $href; ?>" class="btn custom-btn get-started">Get Started!</a>
            <a href="public_projects.php" class="btn btn-sm projects-btn view-projects">View Public Projects</a>
        </div>
    </section>

    <!-- Services Side -->
    <section class="services my-5">
        <div class="container-fluid">
            <h2>Why CPMS?</h2>
            <div class="row gap-5 justify-content-center p-4">
                <div class="col-md-3 service-card p-4">
                    <h3>Transparent by Design</h3>
                    <p>Every project detail is open to the public — from planning and funding to completion — ensuring honest and traceable progress.</p>
                </div>
                <div class="col-md-3 service-card p-4">
                    <h3>Accountable Governance</h3>
                    <p>Manage manpower, equipment, and materials efficiently to reduce waste and increase profitability.</p>
                </div>
                <div class="col-md-3 service-card p-4">
                    <h3>Efficient Project Oversight</h3>
                    <p>Connect contractors, engineers, and clients with one platform — from blueprints to completion.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Projects Side -->
    <!-- <section class="projects my-5">
        <div class="container-fluid">
            <h2>Our Latest Projects</h2>
            <div class="row gap-4 p-4 justify-content-center">
                <div class="col-md-4">
                    <div class="project-card">
                        <img src="assets/images/project-1.jpg" alt="Project 1 Description" class="project-image">
                    </div>
                </div>
                <div class="col-md-4">  
                    <div class="project-card">
                        <img src="assets/images/project-2.jpg" alt="Project 2 Description" class="project-image">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="project-card">
                        <img src="assets/images/project-3.jpg" alt="Project 3 Description" class="project-image">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="project-card">
                        <img src="assets/images/project-4.jpg" alt="Project 4 Description" class="project-image">
                    </div>
                </div>
            </div>
        </div>
    </section>    -->
        
    
  <!-- Team Section -->
    <section id="team" class="py-5">
        <div class="container text-center">
            <h2>Meet Our Team</h2>

            <div id="teamCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2500">
                <div class="carousel-inner">

                    <!-- CARD 1 -->
                    <div class="carousel-item active">
                        <div class="flip-card mx-auto">
                            <div class="flip-card-inner">
                                <div class="flip-card-front d-flex flex-column justify-content-center align-items-center">
                                    <img src="assets/images/danilo.jpg" alt="Jamie" class="team-img mb-3">
                                    <h5 class="fw-bold">Danilo Macaraeg</h5>
                                    <p>UI/UX Designer</p>
                                </div>
                                <div class="flip-card-back d-flex flex-column justify-content-center align-items-center">
                                    <h5>About Danilo</h5>
                                    <p>Expert in user experience and interface design.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CARD 2 -->
                    <div class="carousel-item">
                        <div class="flip-card mx-auto">
                            <div class="flip-card-inner">
                                <div class="flip-card-front d-flex flex-column justify-content-center align-items-center">
                                    <img src="assets/images/vincent_30s.jpg" alt="Jamie" class="team-img mb-3">
                                    <h5 class="fw-bold">Vincent Peraman</h5>
                                    <p>Frontend Developer</p>
                                </div>
                                <div class="flip-card-back d-flex flex-column justify-content-center align-items-center">
                                    <h5>About Vincent</h5>
                                    <p>Handles server logic and database integration.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CARD 3 -->
                    <div class="carousel-item">
                        <div class="flip-card mx-auto">
                            <div class="flip-card-inner">
                                <div class="flip-card-front d-flex flex-column justify-content-center align-items-center">
                                    <img src="assets/images/noah.jpg" alt="Jamie" class="team-img mb-3">
                                    <h5 class="fw-bold">Noah Pascua</h5>
                                    <p>Project Manager</p>
                                </div>
                                <div class="flip-card-back d-flex flex-column justify-content-center align-items-center">
                                    <h5>About Noah</h5>
                                    <p>Ensures timely delivery and smooth coordination.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CARD 4 -->
                    <div class="carousel-item">
                        <div class="flip-card mx-auto">
                            <div class="flip-card-inner">
                                <div class="flip-card-front d-flex flex-column justify-content-center align-items-center">
                                    <img src="assets/images/charles.jpg" alt="Jamie" class="team-img mb-3">
                                    <h5 class="fw-bold">Charles Michael Sarino</h5>
                                    <p>Project Manager</p>
                                </div>
                                <div class="flip-card-back d-flex flex-column justify-content-center align-items-center">
                                    <h5>About Charles Michael</h5>
                                    <p>Ensures timely delivery and smooth coordination.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- About & Contact Section -->
<section class="container-fluid about-contact-section py-5">
    <div class="container">
        <div class="row align-items-start g-5">
            <!-- About -->
            <div id="about" class="col-md-6">
                <h2 class="fw-bold mb-3 text-gradient">About Us</h2>
                <p class="lead mb-4">
                    CPMS is designed to simplify and streamline construction project management.
                    Whether you're handling small residential builds or large industrial sites,
                    our tools help you stay on track, reduce delays, and deliver exceptional results.
                </p>
            </div>

            <div class="col-md-1 d-none d-md-flex justify-content-center">
                <div class="vertical-divider"></div>
            </div>

            <!-- Contact -->
            <div id="contact" class="col-md-5">
                <h2 class="fw-bold mb-3 text-gradient">Contact Us</h2>
                <p class="mb-4">
                    Have questions or need assistance? We're here to help! Get in touch with our team.
                </p>
                <ul class="list-unstyled contact-info">
                    <li class="mb-3">
                        <i class="bi bi-envelope-fill me-2 text-accent"></i>
                        <strong>Email:</strong> cpms.constructions@gmail.com
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-telephone-fill me-2 text-accent"></i>
                        <strong>Phone:</strong> +63 *** *** ****
                    </li>
                    <li>
                        <i class="bi bi-geo-alt-fill me-2 text-accent"></i>
                        <strong>Address:</strong> Based from Cavite
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

  <footer class="footer py-3">
        <div class="container text-center">
            <p class="mb-1 fw-semibold">
                &copy; 2025 <span class="cpms-p">Construction Project Management System</span>
            </p>
            <small>All rights reserved.</small>
        </div>
    </footer>






<?php include 'includes/footer.php'; ?>