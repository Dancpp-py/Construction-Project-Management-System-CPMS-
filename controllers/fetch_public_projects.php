<?php
include_once '../config/db.php';
header('Content-Type: application/json');

$countQuery = "SELECT COUNT(*) as total FROM tbl_project WHERE project_status IN ('Ongoing', 'Completed')";
$countResult = $conn->query($countQuery);
$totalRecords = $countResult->fetch_assoc()['total'];

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
    WHERE p.project_status IN ('Ongoing', 'Completed')  -- Only show public projects
    ORDER BY p.project_id DESC
";
$result = $conn->query($projectsQuery);

$data = [];
$counter = $totalRecords;
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $projectId = $row['project_id'];
        $row['materials'] = $materialsData[$projectId] ?? 'No materials listed';
        $row['employees'] = $employeesData[$projectId] ?? 'No workers assigned';
        $row['project_number'] = $counter--;
        $data[] = $row;
    }
}

echo json_encode([
    'data' => $data,
    'recordsTotal' =>$totalRecords
]);
$conn->close();
?>