<?php
session_start();
include 'config/db.php';
include 'includes/header.php';
include 'includes/navbar.php';
include 'controllers/public_dashboard_count.php';
?>

<!-- HERO / INTRO SECTION -->
<section class="hero-section-public text-center text-light py-5">
    <div class="container py-5">
        <h1 class="fw-bold mb-3 text-gradient">Explore Ongoing Construction Projects</h1>
        <p class="mb-4 hero-text">
            The Construction Project Management System (CPMS) provides public visibility into infrastructure developments — 
            promoting transparency and progress monitoring across all projects.
        </p>
        <a href="#publicProjectsTable" class="btn btn-primary px-4 py-2">View Projects</a>
    </div>
</section>

<!-- PROJECT STATISTICS SECTION -->
<section class="stats-section py-5">
    <div class="container text-center text-light">
        <h2 class="fw-bold mb-5 text-gradient">Project Overview</h2>
        <div class="row g-4 justify-content-center">
            <div class="col-12 col-md-4">
                <div class="stat-card p-4 rounded-4 shadow-sm total">
                    <h3 class="fw-bold mb-1"><?php echo $total_projects?></h3>
                    <p class="mb-0">Total Projects</p>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="stat-card p-4 rounded-4 shadow-sm completed">
                    <h3 class="fw-bold mb-1"><?php echo $completed_projects?></h3>
                    <p class="mb-0">Completed</p>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="stat-card p-4 rounded-4 shadow-sm in-progress">
                    <h3 class="fw-bold mb-1"><?php echo$ongoing_projects?></h3></h3>
                    <p class="mb-0">Ongoing</p>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- EXISTING DATA TABLE SECTION  -->
<section class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Construction Projects</h2>
    </div>
    <div class="table-responsive">
        <table id="publicProjectsTable" class="table table-bordered table-striped" style="width: 100%;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Location</th>
                    <th>Contractor</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Status</th>
                    <th>Budget</th>
                    <th>Materials</th>
                    <th>Employees</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</section>

<section id="news">
    <div class="container">
        <h2 class="section-title text-center mb-4">News & Announcements</h2>
        <div class="list-group">
            <a href="" class="list-group-item list-group-item-action">
                <h5>Road Widening Project Approved</h5>
                <small>Posted on Jaunary 05, 2026</small>
            </a>
            <a href="" class="list-group-item list-group-item-action">
                <h5>School Building Development Project</h5>
                <small>Posted on October 18, 2025</small>
            </a>
            <a href="" class="list-group-item list-group-item-action">
                <h5>Bridge Maintenance Schedule Released</h5>
                <small>Posted on Jaunary 05, 2026</small>
            </a>
            
        </div>
    </div>
</section>

<!--  FAQ SECTION  -->
<section>
  <div class="faq-section py-5">
    <div class="container">
    <h3 class="faq-title text-center mb-4">Frequently Asked Questions</h3>
        
        <div class="accordion accordion-flush" id="faqAccordion">
            <!-- FAQ Item 1 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="faqHeading1">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                            data-bs-target="#faqCollapse1" aria-expanded="false" aria-controls="faqCollapse1">
                        What is this website about?
                    </button>
                </h2>
                <div id="faqCollapse1" class="accordion-collapse collapse" aria-labelledby="faqHeading1" 
                    data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        This system provides public access to information about ongoing and completed construction projects. It promotes transparency and allows citizens to stay updated on local developments.
                    </div>
                </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="faqHeading2">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                            data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
                        How accurate are the project details shown?
                    </button>
                </h2>
                <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faqHeading2" 
                    data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        All data are based on official records submitted by contractors and verified by administrators. Updates are made regularly as projects progress.
                    </div>
                </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="faqHeading3">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                            data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
                        Can I report incorrect project information?
                    </button>
                </h2>
                <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faqHeading3" 
                    data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes. If you notice any outdated or inaccurate details, you may contact our office or use the feedback section provided on the site.
                    </div>
                </div>
            </div>
            <!-- FAQ Item 4 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="faqHeading5">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                            data-bs-target="#faqCollapse5" aria-expanded="false" aria-controls="faqCollapse5">
                        Who manages this system?
                    </button>
                </h2>
                <div id="faqCollapse5" class="accordion-collapse collapse" aria-labelledby="faqHeading5" 
                    data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        The Construction Project Management System is managed by authorized personnel to ensure accurate and reliable project information for public viewing.
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>

<!-- TRANSPARENCY & CONTACT SECTION -->
<section class="transparency-section py-5">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-md-6">
                <h3 class="fw-bold mb-3 text-gradient">Commitment to Transparency</h3>
                <p class="text-secondary mb-4">
                    CPMS is dedicated to promoting public accountability in every construction project. 
                    By making real-time data accessible, we ensure that project timelines, budgets, and outcomes are 
                    visible to the communities they serve.
                </p>
                <ul class="list-unstyled text-secondary">
                    <li>✅ Real-time project tracking</li>
                    <li>✅ Verified contractor information</li>
                    <li>✅ Clear and public performance indicators</li>
                </ul>
            </div>

            <div class="col-md-6">
                <div class="contact-card p-4 rounded-4 shadow-sm">
                    <h4 class="fw-bold mb-3 text-gradient">Contact Us</h4>
                    <p class="mb-2"><i class="bi bi-envelope me-2"></i> cpms.constructions@gmail.com</p>
                    <p class="mb-2"><i class="bi bi-telephone me-2"></i> +63 *** *** ****</p>
                    <p><i class="bi bi-geo-alt me-2"></i> Based from Cavite, Philippines</p>
                    <button type="button" class="btn btn-sm mt-3" data-bs-toggle="modal" data-bs-target="#messageModal">
                        Send a Message
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- MESSAGE MODAL -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content contact-modal">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-gradient" id="messageModalLabel">Send a Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="messageForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Your Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Enter your name">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Your Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter your email">
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" rows="4" placeholder="Type your message here..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="messageForm" class="btn btn-primary">Send</button>
            </div>
        </div>
    </div>
</div>

<!-- FOOTER -->
<footer class="footer py-3">
    <div class="container text-center">
        <p class="mb-1 fw-semibold">
            &copy; 2025 <span class="cpms-p">Construction Project Management System</span>
        </p>
        <small>All rights reserved.</small>
    </div>
</footer>

<?php include 'includes/footer.php'; ?>
