<?php
session_start();
include_once '../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['contractor_id']) || !isset($_GET['project_id'])) {
    echo json_encode([]);
    exit();
}

$project_id = $_GET['project_id'];
$contractor_id = $_SESSION['contractor_id'];

$stmt = $conn->prepare("
    SELECT m.material_id, m.material_name, m.material_price, m.material_quantity 
    FROM tbl_materials m 
    JOIN tbl_project p ON m.project_id = p.project_id 
    WHERE m.project_id = ? AND p.contractor_id = ?
");
$stmt->bind_param("ii", $project_id, $contractor_id);
$stmt->execute();
$result = $stmt->get_result();

$materials = [];
while ($row = $result->fetch_assoc()) {
    $materials[] = $row;
}

echo json_encode($materials);
$stmt->close();
$conn->close();
?>