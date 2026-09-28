<?php 
session_start();
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

include '../../auth/session.php';
include '../../controllers/add_employee.php';
?>


<main class="main-content">
<div class="container">
  <div class="card shadow-sm">
    <div class="card-header bg-success text-white">
      <h4 class="mb-0">Add Employee</h4>
    </div>
    <div class="card-body">
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Employee Name</label>
          <input type="text" class="form-control" name="employee_name" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Role</label>
          <input type="text" class="form-control" name="role" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Salary</label>
          <input type="number" class="form-control" name="salary" required>
        </div>
        <button type="submit" class="btn btn-success">Save Employee</button>
      </form>
    </div>
  </div>
</div>
</main>
<?php include '../../includes/footer.php'; ?>
