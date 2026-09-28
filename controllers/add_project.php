<?php
include_once '../config/db.php';

header('Content-Type: application/json');

$response = ["success" => false, "message" => "Unknown error occurred."];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // COLLECT PROJECT INFO
    $project_name     = $_POST['project_name'];
    $project_type     = $_POST['project_type'];
    $project_location = $_POST['project_location'];
    $project_start    = $_POST['project_start'];
    $project_end      = $_POST['project_end'];
    $project_status   = $_POST['project_status'] ?? 'Pending';
    $contractor_id    = !empty($_POST['contractor_id']) ? $_POST['contractor_id'] : NULL;

    // INSERT PROJECT
    $stmt = $conn->prepare("INSERT INTO tbl_project (project_name, project_type, project_location, project_start, project_end, project_status, contractor_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $project_name, $project_type, $project_location, $project_start, $project_end, $project_status, $contractor_id);

    if ($stmt->execute()) {
        $project_id   = $stmt->insert_id;
        $total_budget = 0;

        // INSERT MATERIAL
        if (!empty($_POST['material_name'])) {
            $stmtMat = $conn->prepare("INSERT INTO tbl_materials (project_id, material_name, material_price, material_quantity) VALUES (?, ?, ?, ?)");

            foreach ($_POST['material_name'] as $i => $material_name) {
                $material_price    = $_POST['material_price'][$i] ?? 0;
                $material_quantity = $_POST['material_quantity'][$i] ?? 0;

                $stmtMat->bind_param("isdi", $project_id, $material_name, $material_price, $material_quantity);
                $stmtMat->execute();

                $total_budget += $material_price * $material_quantity;
            }
            $stmtMat->close();
        }

        // INSERT EMPLOYEE
        if (!empty($_POST['employee_ids'])) {

            $stmtEmp = $conn->prepare("INSERT INTO tbl_project_employee (project_id, employee_id) VALUES (?, ?)");

            foreach ($_POST['employee_ids'] as $empId) {
                $stmtEmp->bind_param("ii", $project_id, $empId);
                $stmtEmp->execute();

                // Add employee salary to budget
                $salaryRes = $conn->query("SELECT employee_salary FROM tbl_employee WHERE employee_id = $empId");
                if ($salaryRes && $row = $salaryRes->fetch_assoc()) {
                    $total_budget += $row['employee_salary'];
                }
            }
            $stmtEmp->close();
        }

        // UPDATE PROJECT BUDGET
        $stmtUpd = $conn->prepare("
            UPDATE tbl_project 
            SET project_budget = ? 
            WHERE project_id = ?
        ");
        $stmtUpd->bind_param("di", $total_budget, $project_id);
        $stmtUpd->execute();
        $stmtUpd->close();

        $response = [
            "success" => true,
            "message" => "Project, materials, and employees inserted successfully.",
            "project_id" => $project_id,
            "budget" => $total_budget
        ];

    } else {   
        $response = [
            "success" => false,
            "message" => "Error inserting project: " . $stmt->error
        ];
    }
    $stmt->close();
}

$conn->close();
echo json_encode($response);
?>
