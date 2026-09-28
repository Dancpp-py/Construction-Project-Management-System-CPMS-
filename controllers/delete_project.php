<?php
include_once '../config/db.php';

if(isset($_POST['project_id'])){
    $id   = $_POST['project_id'];
    $stmt = $conn->prepare("DELETE FROM tbl_project WHERE project_id=?");
    $stmt->bind_param("i",$id);

    if($stmt->execute()){
        echo json_encode(['success'=>true]);
    } else {
        echo json_encode(['success'=>false,'error'=>$stmt->error]);
    }
    $stmt->close();
}
$conn->close();
?>
