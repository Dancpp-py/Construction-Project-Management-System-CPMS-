<?php
session_start();
include_once '../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['contractor_id'])) {
    echo json_encode(["employees" => []]);
    exit();
}

$contractor_id = $_SESSION['contractor_id'];

$query = "
    SELECT employee_id, employee_name, employee_role, employee_salary
    FROM tbl_employee 
    WHERE contractor_id = ?
    ORDER BY employee_name ASC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $contractor_id);
$stmt->execute();
$result = $stmt->get_result();

$employees = [];
while ($row = $result->fetch_assoc()) {
    $employees[] = $row;
}

echo json_encode(["employees" => $employees]);
$stmt->close();
$conn->close();
?>