<?php
session_start();
include_once '../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['contractor_id'])) {
    echo json_encode(["data" => []]);
    exit();
}

$contractor_id = $_SESSION['contractor_id'];

$countQuery = "SELECT COUNT(*) as total FROM tbl_project WHERE contractor_id = ?";
$countStmt = $conn->prepare($countQuery);
$countStmt->bind_param("i", $contractor_id);
$countStmt->execute();
$countResult = $countStmt->get_result();
$totalRecords = $countResult->fetch_array()['total'];
$countStmt->close();

$materialsQuery = "
    SELECT project_id, 
           GROUP_CONCAT(CONCAT(material_name, ' (', material_quantity, ')') SEPARATOR ', ') AS materials
    FROM tbl_materials
    GROUP BY project_id
";
$materialsResult = $conn->query($materialsQuery);
$materialsData = [];
if ($materialsResult) {
    while ($row = $materialsResult->fetch_assoc()) {
        $materialsData[$row['project_id']] = $row['materials'];
    }
}


$employeesQuery = "
    SELECT pe.project_id, 
           GROUP_CONCAT(CONCAT(e.employee_name, ' (', e.employee_role, ')') SEPARATOR ', ') AS employees
    FROM tbl_project_employee pe
    JOIN tbl_employee e ON pe.employee_id = e.employee_id
    GROUP BY pe.project_id
";
$employeesResult = $conn->query($employeesQuery);
$employeesData = [];
if ($employeesResult) {
    while ($row = $employeesResult->fetch_assoc()) {
        $employeesData[$row['project_id']] = $row['employees'];
    }
}


$projectsQuery = "
    SELECT p.*, c.contractor_name
    FROM tbl_project p
    LEFT JOIN tbl_contractor c ON p.contractor_id = c.contractor_id
    WHERE p.contractor_id = ?
    ORDER BY p.project_id DESC
";

$stmt = $conn->prepare($projectsQuery);
$stmt->bind_param("i", $contractor_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
$counter = $totalRecords;
while ($row = $result->fetch_assoc()) {
    $projectId = $row['project_id'];
    $row['materials'] = $materialsData[$projectId] ?? '';
    $row['employees'] = $employeesData[$projectId] ?? '';
    $row['project_number'] = $counter--;
    $data[] = $row;
}

echo json_encode([
    'data' => $data,
    'recordsTotal' => $totalRecords
]);
$conn->close();

?>