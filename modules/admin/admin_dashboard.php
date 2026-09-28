<?php 
session_start();
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../controllers/admin_dashboard_count.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: /CPMS/login_form.php");
    exit();
}
?>

<div class="sidebar vh-100 position-fixed bg-dark top-0 z-3 d-flex flex-column" id="sidebar">
    <div class="py-3">   
        <h4 class="text-center">Admin Panel</h4>
        <hr class="border-light">
    </div>

    <ul class="nav flex-column gap-2">
        <li class="nav-item">
            <a href="/CPMS/modules/admin/admin_dashboard.php" class="nav-link text-white">
                <i class="bi bi-file-bar-graph-fill me-2"></i> Manage Projects
            </a>
        </li>

         <li class="nav-item">
            <a href="#adminMenu" 
            class="nav-link text-white" 
            role="button"
            data-bs-toggle="collapse"
            aria-expanded="false"
            aria-controls="adminMenu">
                <span><i class="bi bi-people-fill me-2"></i> Manage Admin</span>
                <i class="bi bi-caret-down-fill fs-6"></i>
            </a>
            <ul class="collapse nav ps-4" id="adminMenu">
                <li><a href="/CPMS/modules/admin/add_admin.php" class="nav-link text-white"><i class="bi bi-plus-circle me-2"></i> Add Admin</a></li>
            </ul>
        </li>

        <li class="nav-item">
            <a href="#contractorMenu" 
            class="nav-link text-white" 
            role="button"
            data-bs-toggle="collapse"
            aria-expanded="false"
            aria-controls="contractorMenu">
                <span><i class="bi bi-people-fill me-2"></i> Manage Contractors</span>
                <i class="bi bi-caret-down-fill fs-6"></i>
            </a>
            <ul class="collapse nav ps-4" id="contractorMenu">
                <li><a href="/CPMS/modules/contractors/add_contractor.php" class="nav-link text-white"><i class="bi bi-plus-circle me-2"></i> Add Contractor</a></li>
                <li><a href="/CPMS/modules/contractors/contractor_dashboard.php" class="nav-link text-white"><i class="bi bi-person-vcard me-2"></i>View Dashboard</a></li>
            </ul>
        </li>
    </ul>
</div>

<div class="sidebar-overlay position-fixed top-0 w-100 h-100"></div>

<main class="main-content">
    <div class="row justify-content-evenly g-4 p-4">
        <div class="d-flex align-items-center mb-4">
            <h1>Dashboard</h1>
        </div>
        
        <div class="col-md-4">
            <div class="card db-card ">
                <div class="card-body card-first">
                    <i class="bi bi-people-fill fs-2"></i>
                    <h5 class="card-title">Contractors</h5>
                    <h2 class="card-text"><?php echo $total_contractors; ?></h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card db-card">
                <div class="card-body card-second">
                    <i class="bi bi-kanban-fill fs-2"></i>
                    <h5 class="card-title">Projects</h5>
                    <h2 class="card-text"><?php echo $total_projects; ?></h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row justify-content-center g-4 mb-4 p-4">
        <div class="col-md-4">
            <div class="card db-card">
                <div class="card-body card-pending-status">
                    <i class="bi bi-hourglass-split fs-2"></i>
                    <h5 class="card-title">Pending Projects</h5>
                    <h2 class="card-text"><?php echo $pending_projects; ?></h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card db-card">
                <div class="card-body card-ongoing-status">
                    <i class="bi bi-arrow-repeat fs-2"></i>
                    <h5 class="card-title">Ongoing Projects</h5>
                    <h2 class="card-text"><?php echo $ongoing_projects; ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card db-card">
                <div class="card-body card-completed-status">
                    <i class="bi bi-check-circle-fill fs-2"></i>
                    <h5 class="card-title">Completed Projects</h5>
                    <h2 class="card-text"><?php echo $completed_projects; ?></h2>
                </div>
            </div>
        </div>
    </div>

    <hr class="border-subtle">
    
    <section class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Projects Overview</h2>
            <button class="btn custom-btn text-white" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                <i class="bi bi-plus-circle-fill"></i> Add New Project
            </button>
        </div>
    
        <div class="table-responsive">
            <table id="projectsTable" class="table table-bordered table-striped table-hover" style="width: 100%;">
                <thead>
                    <tr>
                        <th>ID</th>
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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </section>
</main>

<!-- Add Project Modal -->
<div class="modal fade" id="addProjectModal" tabindex="-1" aria-labelledby="addProjectLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProjectLabel">Add New Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="projectForm">
                    <!-- Project Info -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Project Name</label>
                            <input type="text" class="form-control" name="project_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Project Type</label>
                            <input type="text" class="form-control" name="project_type" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control" name="project_location" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="project_start" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" name="project_end" required>
                        </div>
                    </div>

                    <!-- Contractor Assignment Section -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Assign to Contractor</label>
                            <select class="form-select" name="contractor_id">
                                <option value="">Default (Admin Project)</option>
                                <?php
                                $contractors = $conn->query("SELECT contractor_id, contractor_name FROM tbl_contractor");
                                while ($contractor = $contractors->fetch_assoc()) {
                                    echo "<option value='{$contractor['contractor_id']}'>{$contractor['contractor_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Project Status</label>
                            <select class="form-select" name="project_status" required>
                                <option value="Pending">Pending</option>
                                <option value="Ongoing">Ongoing</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                    </div>

                    <hr>
                    <!-- Materials Section -->
                    <h5>Materials</h5>
                    <div id="materialsContainer">
                        <div class="row mb-2 gap-3 p-2 material-item">
                            <div class="col-12">
                                <input type="text" name="material_name[]" class="form-control" placeholder="Material Name" required>
                            </div>
                            <div class="col-12">
                                <input type="number" name="material_price[]" class="form-control" placeholder="Price" step="0.01" required>
                            </div>
                            <div class="col-12">
                                <input type="number" name="material_quantity[]" class="form-control" placeholder="Quantity" required>
                            </div>
                            <div>
                                <button type="button" id="addMaterialBtn" class="btn btn-sm btn-secondary">+ Add Material</button>
                                <button type="button" class="btn btn-sm btn-danger removeMaterialBtn">Remove</button>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <!-- Employees Section -->
                    <h5>Assign Employees</h5>
                    <div class="mb-3">
                        <label class="form-label">Select Employees (hold Ctrl to select multiple)</label>
                        <select class="form-select" name="employee_ids[]" multiple size="5">
                            <?php
                                $employees = $conn->query("SELECT employee_id, employee_role, employee_name FROM tbl_employee");
                                if ($employees && $employees->num_rows > 0) {
                                    while ($row = $employees->fetch_assoc()) {
                                        echo "<option value='{$row['employee_id']}'>{$row['employee_name']} (Role: {$row['employee_role']})</option>";
                                    }
                                } else {
                                    echo "<option disabled>No employees found</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Project</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Project Modal -->
<div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProjectLabel">Edit Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editProjectForm">
                    <input type="hidden" name="project_id" id="edit_project_id">
                    <div class="mb-3">
                        <label>Project Name</label>
                        <input type="text" class="form-control" name="project_name" id="edit_project_name" required>
                    </div>
                    <div class="mb-3">
                        <label>Project Type</label>
                        <input type="text" class="form-control" name="project_type" id="edit_project_type" required>
                    </div>
                    <div class="mb-3">
                        <label>Location</label>
                        <input type="text" class="form-control" name="project_location" id="edit_project_location" required>
                    </div>
                    <div class="mb-3">
                        <label>Start Date</label>
                        <input type="date" class="form-control" name="project_start" id="edit_project_start" required>
                    </div>
                    <div class="mb-3">
                        <label>End Date</label>
                        <input type="date" class="form-control" name="project_end" id="edit_project_end" required>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select class="form-select" name="project_status" id="edit_project_status" required>
                            <option value="Pending">Pending</option>
                            <option value="Ongoing">Ongoing</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Budget</label>
                        <input type="number" class="form-control" name="project_budget" id="edit_project_budget" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Project</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
