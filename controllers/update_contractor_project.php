<?php
session_start();
include_once '../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['contractor_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access"]);
    exit;
}

$contractor_id = intval($_SESSION['contractor_id']);
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

function respond($arr) {
    echo json_encode($arr);
    exit;
}

$project_id       = intval($_POST['project_id'] ?? 0);
$project_name     = trim($_POST['project_name'] ?? '');
$project_type     = trim($_POST['project_type'] ?? '');
$project_location = trim($_POST['project_location'] ?? '');
$project_start    = $_POST['project_start'] ?? '';
$project_end      = $_POST['project_end'] ?? '';

if ($project_id <= 0) respond(["success" => false, "message" => "Invalid project ID"]);
if ($project_name === '') respond(["success" => false, "message" => "Project name is required"]);
if ($project_start === '' || $project_end === '') respond(["success" => false, "message" => "Start and end dates are required"]);

$today = date('Y-m-d');
if ($project_start < $today || $project_end < $today) respond(["success" => false, "message" => "Dates cannot be in the past!"]);
if ($project_start > $project_end) respond(["success" => false, "message" => "End date cannot be before start date!"]);

$check = $conn->prepare("SELECT project_id FROM tbl_project WHERE project_id = ? AND contractor_id = ?");
$check->bind_param("ii", $project_id, $contractor_id);
$check->execute();
$result = $check->get_result();
if ($result->num_rows === 0) respond(["success" => false, "message" => "Project not found or access denied."]);
$check->close();

$conn->begin_transaction();

try {
    $update = $conn->prepare("
        UPDATE tbl_project 
        SET project_name=?, project_type=?, project_location=?, project_start=?, project_end=? 
        WHERE project_id=? AND contractor_id=?
    ");
    $update->bind_param("sssssii", $project_name, $project_type, $project_location, $project_start, $project_end, $project_id, $contractor_id);
    $update->execute();
    $update->close();

    $names = $_POST['material_names'] ?? [];
    $prices = $_POST['material_prices'] ?? [];
    $quantities = $_POST['material_quantities'] ?? [];
    $ids = $_POST['material_ids'] ?? [];

    $submitted_material_ids = [];
    
    for ($i = 0; $i < count($names); $i++) {
        $name = trim($names[$i]);
        $price = floatval($prices[$i] ?? 0);
        $qty = intval($quantities[$i] ?? 0);
        $mid = $ids[$i] ?? '';

        if ($name === '') continue;

        if ($mid === 'new' || $mid === '') {
            $insert = $conn->prepare("INSERT INTO tbl_materials (project_id, material_name, material_price, material_quantity) VALUES (?, ?, ?, ?)");
            $insert->bind_param("isdi", $project_id, $name, $price, $qty);
            $insert->execute();
            $insert->close();
        } else {
            $mid_int = intval($mid);
            $submitted_material_ids[] = $mid_int;
            
            $updateMat = $conn->prepare("UPDATE tbl_materials SET material_name=?, material_price=?, material_quantity=? WHERE material_id=? AND project_id=?");
            $updateMat->bind_param("sdiii", $name, $price, $qty, $mid_int, $project_id);
            $updateMat->execute();
            $updateMat->close();
        }
    }

    if (!empty($submitted_material_ids)) {
        $placeholders = implode(',', array_fill(0, count($submitted_material_ids), '?'));
        $delete_query = "DELETE FROM tbl_materials WHERE project_id = ? AND material_id NOT IN ($placeholders)";
        $deleteStmt = $conn->prepare($delete_query);
        
        $types = "i" . str_repeat("i", count($submitted_material_ids));
        $params = array_merge([$project_id], $submitted_material_ids);
        
        $deleteStmt->bind_param($types, ...$params);
        $deleteStmt->execute();
        $deleteStmt->close();
    } else {
        $deleteAll = $conn->prepare("DELETE FROM tbl_materials WHERE project_id = ?");
        $deleteAll->bind_param("i", $project_id);
        $deleteAll->execute();
        $deleteAll->close();
    }

    if (isset($_POST['employee_ids']) && !empty($_POST['employee_ids'])) {
        $posted = array_map('intval', $_POST['employee_ids']);

        $del = $conn->prepare("DELETE FROM tbl_project_employee WHERE project_id = ?");
        $del->bind_param("i", $project_id);
        $del->execute();
        $del->close();

        $insertEmp = $conn->prepare("INSERT INTO tbl_project_employee (project_id, employee_id) VALUES (?, ?)");
        foreach ($posted as $eid) {
            $chk = $conn->prepare("SELECT employee_id FROM tbl_employee WHERE employee_id=? AND contractor_id=?");
            $chk->bind_param("ii", $eid, $contractor_id);
            $chk->execute();
            $chkRes = $chk->get_result();
            if ($chkRes->num_rows > 0) {
                $insertEmp->bind_param("ii", $project_id, $eid);
                $insertEmp->execute();
            }
            $chk->close();
        }
        $insertEmp->close();
    }
    
    $conn->commit();
    respond(["success" => true, "message" => "Project updated successfully!"]);

} catch (Exception $e) {
    $conn->rollback();
    respond(["success" => false, "message" => "Update failed: " . $e->getMessage()]);
}

$conn->close();
?>