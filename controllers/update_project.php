<?php
include_once '../config/db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $id       = $_POST['project_id'];
    $name     = $_POST['project_name'];
    $type     = $_POST['project_type'];
    $location = $_POST['project_location'];
    $start    = $_POST['project_start'];
    $end      = $_POST['project_end'];
    $status   = $_POST['project_status'];
    $budget   = $_POST['project_budget'];

    $stmt = $conn->prepare("UPDATE tbl_project SET project_name=?, project_type=?, project_location=?, project_start=?, project_end=?, project_status=?, project_budget=? WHERE project_id=?");
    $stmt->bind_param("ssssssdi",$name,$type,$location,$start,$end,$status,$budget,$id);

    if($stmt->execute()){
        echo json_encode(['success'=>true]);
    } else {
        echo json_encode(['success'=>false, 'error'=>$stmt->error]);
    }
    $stmt->close();
}
$conn->close();
?>
