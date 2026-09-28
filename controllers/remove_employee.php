<?php
session_start();
include_once '../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['contractor_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit();
}

$contractor_id = intval($_SESSION['contractor_id']);
$employee_id = intval($_POST['employee_id'] ?? 0);

if ($employee_id <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid employee ID"]);
    exit();
}

$check = $conn->prepare("
    SELECT employee_id FROM tbl_employee 
    WHERE employee_id = ? AND contractor_id = ?
");
$check->bind_param("ii", $employee_id, $contractor_id);
$check->execute();
$checkResult = $check->get_result();

if ($checkResult->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Employee not found or access denied"]);
    $check->close();
    exit();
}

$check->close();

$activeProjectCheck = $conn->prepare("
    SELECT COUNT(*) as count 
    FROM tbl_project_employee pe
    JOIN tbl_project p ON pe.project_id = p.project_id
    WHERE pe.employee_id = ? 
    AND p.project_status IN ('Ongoing', 'Pending')
");
$activeProjectCheck->bind_param("i", $employee_id);
$activeProjectCheck->execute();
$projectResult = $activeProjectCheck->get_result();
$projectCount = $projectResult->fetch_assoc()['count'];
$activeProjectCheck->close();

if ($projectCount > 0) {
    echo json_encode([
        "success" => false, 
        "message" => "Cannot remove employee. They are currently assigned to $projectCount active project(s). Please reassign them first."
    ]);
    exit();
}

$conn->begin_transaction();

try {
    $delProjects = $conn->prepare("
        DELETE FROM tbl_project_employee 
        WHERE employee_id = ?
    ");
    $delProjects->bind_param("i", $employee_id);
    $delProjects->execute();
    $delProjects->close();

    $deleteEmp = $conn->prepare("
        DELETE FROM tbl_employee 
        WHERE employee_id = ? AND contractor_id = ?
    ");
    $deleteEmp->bind_param("ii", $employee_id, $contractor_id);
    $deleteEmp->execute();
    $affectedRows = $deleteEmp->affected_rows;
    $deleteEmp->close();

    if ($affectedRows > 0) {
        $conn->commit();
        echo json_encode([
            "success" => true,
            "message" => "Employee removed successfully!"
        ]);
    } else {
        $conn->rollback();
        echo json_encode([
            "success" => false,
            "message" => "Failed to remove employee"
        ]);
    }

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $e->getMessage()
    ]);
}

$conn->close();
?>