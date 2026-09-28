<?php
include_once '../config/db.php';

header('Content-Type: application/json');


$countQuery = "SELECT COUNT(*) as total FROM tbl_project";
$countResult = $conn->query($countQuery);
$totalRecords = $countResult->fetch_assoc()['total'];

// MATERIALS QUERY
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

// EMPLOYEES QUERY
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

// PROJECTS QUERY
$projectsQuery = "
    SELECT p.*, c.contractor_name 
    FROM tbl_project p 
    LEFT JOIN tbl_contractor c ON p.contractor_id = c.contractor_id 
    ORDER BY p.project_id DESC
";
$result = $conn->query($projectsQuery);

// FETCH DATA
$data = [];
$counter = $totalRecords;
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $projectId = $row['project_id'];
        $row['materials'] = $materialsData[$projectId] ?? '';
        $row['employees'] = $employeesData[$projectId] ?? '';
        $row['project_number'] = $counter--; 
        $data[] = $row;
    }
}

echo json_encode([
    'data' => $data,
    'recordsTotal' => $totalRecords
]);
$conn->close();
?>