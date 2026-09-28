<?php
session_start();
include_once '../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['contractor_id'])) {
    echo json_encode([]);
    exit();
}

$contractor_id = $_SESSION['contractor_id'];

$available_query = "
    SELECT e.employee_id, e.employee_name, e.employee_role 
    FROM tbl_employee e 
    WHERE e.contractor_id = ? 
    AND e.employee_id NOT IN (
        SELECT pe.employee_id 
        FROM tbl_project_employee pe 
        JOIN tbl_project p ON pe.project_id = p.project_id 
        WHERE p.project_status IN ('Ongoing', 'Pending')
    )
    ORDER BY e.employee_name
";

$busy_query = "
    SELECT e.employee_id, e.employee_name, e.employee_role, p.project_name
    FROM tbl_employee e 
    JOIN tbl_project_employee pe ON e.employee_id = pe.employee_id
    JOIN tbl_project p ON pe.project_id = p.project_id 
    WHERE e.contractor_id = ? 
    AND p.project_status IN ('Ongoing', 'Pending')
    ORDER BY e.employee_name
";

$stmt_available = $conn->prepare($available_query);
$stmt_available->bind_param("i", $contractor_id);
$stmt_available->execute();
$available_result = $stmt_available->get_result();

$available_employees = [];
while ($row = $available_result->fetch_assoc()) {
    $row['status'] = 'available';
    $row['current_project'] = 'Not assigned';
    $available_employees[] = $row;
}
$stmt_available->close();

$stmt_busy = $conn->prepare($busy_query);
$stmt_busy->bind_param("i", $contractor_id);
$stmt_busy->execute();
$busy_result = $stmt_busy->get_result();

$busy_employees = [];
while ($row = $busy_result->fetch_assoc()) {
    $row['status'] = 'busy';
    $busy_employees[] = $row;
}
$stmt_busy->close();

$all_employees = array_merge($available_employees, $busy_employees);

echo json_encode($all_employees);
$conn->close();
?>