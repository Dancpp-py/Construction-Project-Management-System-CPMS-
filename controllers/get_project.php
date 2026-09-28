<?php
include_once '../config/db.php';

if(isset($_GET['project_id'])){
    $project_id = $_GET['project_id'];

    $stmt = $conn->prepare("SELECT * FROM tbl_project WHERE project_id = ?");
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($row = $result->fetch_assoc()){
        echo json_encode($row);
    } else {
        echo json_encode(['error'=>'Project not found']);
    }
}
$conn->close();
?>
