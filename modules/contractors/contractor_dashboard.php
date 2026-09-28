<?php 
session_start();
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../config/db.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'contractor') {
    header(header: "Location: ../../login_form.php");
    exit();
}
$contractor_id = $_SESSION['contractor_id'];

?>

<main class="mt-5">
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header text-center">
                <h4>Available Employees</h4>
            </div>
            
            <div class="card-body d-flex">
                <!-- Left Column -->
                <div class="left-column">
                    <h5 class="card-title mb-3">Employees</h5>
                    <div id="employee-list">
                        <!-- Dynamic content via AJAX -->
                    </div>
                </div>
                <!-- Right Column -->
                <div class="right-column">
                    <h5>Summary</h5>
                    <p><strong>Total Employees:</strong> <span id="total-employees">0</span></p>
                    <p><strong>Available:</strong> <span id="available-employees-count">0</span></p>
                    <p><strong>Busy:</strong> <span id="busy-employees-count">0</span></p>
                    <div class="mt-2">
                        <span class="badge badge-available me-1">Available</span>
                        <span class="badge badge-in-progress me-1">In Progress</span>
                </div>
            </div>
        </div>
    </div>

    <section class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Projects Overview</h2>

            <div class="d-flex gap-2">
                <button class="btn custom-btn text-white" data-bs-toggle="modal" data-bs-target = "#addEmployeeModal">
                    <i class="bi bi-plus-circle"></i> Add Employee
                </button>

                <button class="btn custom-btn text-white" data-bs-toggle="modal" data-bs-target = "#removeEmployeeModal">
                    <i class="bi bi-dash-circle"></i> Remove Employee
                </button>

                <button class="btn custom-btn text-white" data-bs-toggle="modal" data-bs-target="#contractorAddProjectModal">
                    <i class="bi bi-plus-circle"></i> Add New Project
                </button>
            </div>
        </div>
    
        <div class="table-responsive">
            <table id="contractorProjectsTable" class="table table-bordered table-striped" style="width: 100%;">
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
<div class="modal fade" id="contractorAddProjectModal" tabindex="-1" aria-labelledby="contractorAddProjectLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contractorAddProjectLabel">Add New Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="contractorProjectForm">
                    <div class="mb-3 text-center d-flex flex-column align-items-center">
                        <span class="badge text-white p-2" style="background-color: var(--color-background);">Contractor</span>
                        <span class="fs-5 fw-bold"><?php echo $_SESSION['contractor_name']; ?></span>
                    </div>

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
                            <input type="date" class="form-control" name="project_start" id="project_start" min="<?php echo date('Y-m-d'); ?>"required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" name="project_end" id="project_end" min="<?php echo date('Y-m-d'); ?>"required>
                        </div>
                    </div>

                    
                    <hr>
                    <!-- Materials Section -->
                    <h5>Materials</h5>
                    <div id="contractorMaterialsContainer">
                        <div class="row mb-2 gap-3 p-2 material-item">
                            <div class="col-12">
                                <input type="text" name="material_name[]" class="form-control" placeholder="Material Name" required>
                            </div>
                            <div class="col-12">
                                <input type="number" name="material_price[]" class="form-control" placeholder="Price" step="0.01" min="0" required>
                            </div>
                            <div class="col-12">
                                <input type="number" name="material_quantity[]" class="form-control" placeholder="Quantity" min="0"required>
                            </div>
                            <div>
                                <button type="button" id="contractorAddMaterialBtn" class="btn btn-sm text-white custom-btn">+ Add Material</button>
                                <button type="button" class="btn btn-sm btn-danger removeMaterialBtn">Remove</button>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <!-- Employees Section -->
                    <h5>Assign Employees</h5>
                    <div class="mb-3">
                        <label class="form-label">Select Employees (hold Ctrl to select multiple)</label>
                        <select class="form-select" name="employee_ids[]" multiple size="5" id="contractorEmployees">
                            <?php
                                $employees_query = "
                                SELECT e.employee_id, e.employee_role, e.employee_name 
                                FROM tbl_employee e 
                                WHERE e.contractor_id = $contractor_id 
                                AND e.employee_id NOT IN (
                                    SELECT pe.employee_id 
                                    FROM tbl_project_employee pe 
                                    JOIN tbl_project p ON pe.project_id = p.project_id 
                                    WHERE p.project_status IN ('Ongoing', 'Pending')
                                )
                            ";

                                $employees = $conn->query($employees_query);

                                if ($employees && $employees->num_rows > 0) {
                                    while ($row = $employees->fetch_assoc()) {
                                        echo "<option value='{$row['employee_id']}'>{$row['employee_name']} (Role: {$row['employee_role']})</option>";
                                    }
                                } else {
                                    echo "<option disabled>No employees found</option>";
                                }
                            ?>
                        </select>
                        <div class="form-text text-warning">
                            Only employees not assigned to any ongoing or pending projects are shown.
                        </div>
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
<div class="modal fade" id="contractorEditProjectModal" tabindex="-1" aria-labelledby="contractorEditProjectLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contractorEditProjectLabel">Edit Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="contractorEditProjectForm">
                    <input type="hidden" name="project_id" id="contractor_edit_project_id">
                    <div class="mb-3">
                        <label>Project Name</label>
                        <input type="text" class="form-control" name="project_name" id="contractor_edit_project_name" required>
                    </div>
                    <div class="mb-3">
                        <label>Project Type</label>
                        <input type="text" class="form-control" name="project_type" id="contractor_edit_project_type" required>
                    </div>
                    <div class="mb-3">
                        <label>Location</label>
                        <input type="text" class="form-control" name="project_location" id="contractor_edit_project_location" required>
                    </div>
                    <div class="mb-3">
                        <label>Start Date</label>
                        <input type="date" class="form-control" name="project_start" id="contractor_edit_project_start" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>End Date</label>
                        <input type="date" class="form-control" name="project_end" id="contractor_edit_project_end" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    
                    <hr>

                    <h5>Materials</h5>
                    <div id="contractorEditMaterialsContainer">
                        
                        <div class="text-muted">Loading materials...</div>
                    </div>
                    <button type="button" class="btn btn-sm btn-success mt-2" id="contractorEditAddMaterialBtn">
                        <i class="bi bi-plus-circle"></i> Add New Material
                    </button>
                    
                    <hr>

                    <h5>Assign Employees</h5>
                    <div class="mb-3">
                        <label class="form-label">Select Employees (hold Ctrl to select multiple)</label>
                        <select class="form-select" name="employee_ids[]" multiple size="5" id="contractorEditEmployees">
                    
                        </select>
                        <div class="form-text text-warning" id="contractorEditEmployeeMessage">
                            Only available employees are shown. Current assignments are pre-selected.
                        </div>
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


<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="addEmployeeLabel">Add Employee</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="employeeForm">
                    <div class="mb-3">
                        <label class="form-label">Employee Name</label>
                        <input type="text" class="form-control" name="employee_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <input type="text" class="form-control" name="employee_role" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Salary</label>
                        <input type="number" class="form-control" name="employee_salary" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" name="save_employee">Save Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="removeEmployeeModal" tabindex="-1" aria-labelledby="removeEmployeeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="removeEmployeeLabel">Remove Employee</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="removeEmployeeForm">
                    <div class="mb-3">
                        <label class="form-label">Select Employee to Remove</label>
                        <select class="form-select" name="employee_id" id="removeEmployeeSelect" required>
                            <option value="">-- Choose an employee --</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <strong>Warning:</strong> This will permanently remove the employee from your system.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Remove Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>