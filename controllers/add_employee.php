<?php
session_start();
include_once '../config/db.php';
header('Content-Type: application/json');

$response = ["success" => false, "message" => "Unknown error occurred."];

if (!isset($_SESSION['contractor_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access"]);
    exit();
}

$contractor_id = $_SESSION['contractor_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_name = $_POST['employee_name'] ?? '';
    $employee_role = $_POST['employee_role'] ?? '';
    $employee_salary = $_POST['employee_salary'] ?? 0;

    if (empty(trim($employee_name))) {
        echo json_encode(["success" => false, "message" => "Employee name is required."]);
        exit();
    }

    if (empty(trim($employee_role))) {
        echo json_encode(["success" => false, "message" => "Employee role is required."]);
        exit();
    }

    $salary = floatval($employee_salary);
    if ($salary < 0) {
        echo json_encode(["success" => false, "message" => "Salary cannot be negative."]);
        exit();
    }

    $stmt = $conn->prepare("
        INSERT INTO tbl_employee (employee_name, employee_role, employee_salary, contractor_id) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("ssdi", $employee_name, $employee_role, $salary, $contractor_id);

    if ($stmt->execute()) {
        $response = [
            "success" => true,
            "message" => "Employee added successfully!",
            "employee_id" => $stmt->insert_id
        ];
    } else {
        $response = [
            "success" => false,
            "message" => "Error adding employee: " . $stmt->error
        ];
    }

    $stmt->close();
} else {
    $response = ["success" => false, "message" => "Invalid request method."];
}

echo json_encode($response);
$conn->close();
?>