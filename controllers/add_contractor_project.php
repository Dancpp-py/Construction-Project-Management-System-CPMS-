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

    $project_name = $_POST['project_name'];
    $project_type = $_POST['project_type'];
    $project_location = $_POST['project_location'];
    $project_start = $_POST['project_start'];
    $project_end = $_POST['project_end'];

    $today = date('Y-m-d');

    if ($project_start < $today) {
        echo json_encode(['success' => false, 'message' => 'Start date cannot be in the past!']);
        exit();
    }

    if ($project_end < $today) {
        echo json_encode(['success' => false, 'message' => 'End date cannot be in the past!']);
        exit();
    }

    if ($project_start > $project_end) {
        echo json_encode(["success" => false, "message" => "End date cannot be before start date!"]);
        exit();
    }

    if (!empty($_POST['employee_ids'])) {
        foreach ($_POST['employee_ids'] as $empId) {

            $check_availability = "
            SELECT COUNT(*) as count 
            FROM tbl_project_employee pe 
            JOIN tbl_project p ON pe.project_id = p.project_id 
            WHERE pe.employee_id = ? 
            AND p.project_status IN ('Ongoing', 'Pending')
            ";

            $stmtCheck = $conn->prepare($check_availability);
            $stmtCheck->bind_param("i", $empId);
            $stmtCheck->execute();
            $result = $stmtCheck->get_result();
            $count = $result->fetch_assoc()['count'];
            $stmtCheck->close();

            if ($count > 0) {
                echo json_encode([
                    "success" => false,
                    "message" => "One or more employees are already assigned to another project during this period."
                ]);
                exit();
            }

        }
    }


    $stmt = $conn->prepare("
        INSERT INTO tbl_project (project_name, project_type, project_location, project_start, project_end, project_status, contractor_id) 
        VALUES (?, ?, ?, ?, ?, 'Pending', ?)
    ");
    $stmt->bind_param("sssssi", $project_name, $project_type, $project_location, $project_start, $project_end, $contractor_id);

    if ($stmt->execute()) {   
        $project_id = $stmt->insert_id;
        $total_budget = 0;

        if (!empty($_POST['material_name'])) {
            $stmtMat = $conn->prepare("
                INSERT INTO tbl_materials (project_id, material_name, material_price, material_quantity) 
                VALUES (?, ?, ?, ?)
            ");

            foreach ($_POST['material_name'] as $i => $material_name) {
                $material_price = $_POST['material_price'][$i] ?? 0;
                $material_quantity = $_POST['material_quantity'][$i] ?? 0;

                $stmtMat->bind_param("isdi", $project_id, $material_name, $material_price, $material_quantity);
                $stmtMat->execute();
                $total_budget += $material_price * $material_quantity;
            }
            $stmtMat->close();
        }


        if (!empty($_POST['employee_ids'])) {
            $stmtEmp = $conn->prepare("
                INSERT INTO tbl_project_employee (project_id, employee_id) 
                VALUES (?, ?)
            ");

            foreach ($_POST['employee_ids'] as $empId) {

                $checkEmp = $conn->query("SELECT employee_id FROM tbl_employee WHERE employee_id = $empId AND contractor_id = $contractor_id");
                if ($checkEmp->num_rows === 1) {
                    $stmtEmp->bind_param("ii", $project_id, $empId);
                    $stmtEmp->execute();

                    $salaryRes = $conn->query("SELECT employee_salary FROM tbl_employee WHERE employee_id = $empId");
                    if ($salaryRes && $row = $salaryRes->fetch_assoc()) {
                        $total_budget += $row['employee_salary'];
                    }
                }
            }
            $stmtEmp->close();
        }


        $stmtUpd = $conn->prepare("UPDATE tbl_project SET project_budget = ? WHERE project_id = ?");
        $stmtUpd->bind_param("di", $total_budget, $project_id);
        $stmtUpd->execute();
        $stmtUpd->close();

        $response = [
            "success" => true,
            "message" => "Project submitted successfully for admin approval!",
            "project_id" => $project_id
        ];

    } else {   
        $response = [
            "success" => false,
            "message" => "Error inserting project: " . $stmt->error
        ];
    }

    $stmt->close();
}

echo json_encode($response);
$conn->close();
?>