<?php
session_start();
include_once '../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['contractor_id'])) {
    echo json_encode(["error" => "Unauthorized access"]);
    exit();
}

$contractor_id = $_SESSION['contractor_id'];

if (isset($_GET['project_id'])) {
    $project_id = $_GET['project_id'];
    
    $stmt = $conn->prepare("
        SELECT project_id, project_name, project_type, project_location, project_start, project_end, contractor_id 
        FROM tbl_project 
        WHERE project_id = ? AND contractor_id = ?
    ");
    $stmt->bind_param("ii", $project_id, $contractor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $project = $result->fetch_assoc();
        echo json_encode($project);
    } else {
        echo json_encode(["error" => "Project not found or access denied"]);
    }
    
    $stmt->close();
} else {
    echo json_encode(["error" => "No project ID provided"]);
}

$conn->close();
?>