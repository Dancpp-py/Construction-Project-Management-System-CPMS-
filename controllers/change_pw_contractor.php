<?php  
session_start();
header('Content-Type: application/json');
include_once '../config/db.php';

if(!isset($_SESSION['contractor_id'])) {
    echo json_encode(['message' => 'error', 'error' => 'You must be logged in as contractor']);
    exit;
}

$contractor_id = $_SESSION['contractor_id'];
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if(empty($current_password) || empty($new_password) ||empty($confirm_password)) {
    echo json_encode(['message' => 'error', 'error' => 'All fields are required']);
    exit;
}

if ($new_password !== $confirm_password) {
    echo json_encode(['message' => 'error', 'error' => 'Password does not match']);
    exit;
}

if(strlen($new_password) <8) {
    echo json_encode(['message' => 'error', 'error' => 'Password must be at least 8 characters long']);
    exit;
}

try {
    $stmt = $conn->prepare('SELECT acontractor_password FROM tbl_contractor WHERE contractor_id = ?');
    $stmt->bind_param("i", $contractor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $contractor =$result->fetch_assoc();

    if(!$contractor) {
        echo json_encode(['message' => 'error', 'error' => 'User not found']);
        exit;
    }

    if (!password_verify($current_password, $admin['admin_password'])) {
        echo json_encode(['message' => 'error', 'error' => 'Current password is incorrect']);
        exit;
    }

    if (password_verify($new_password, $admin['admin_password'])) {
        echo json_encode(['message' => 'error', 'error' => 'New password cannot be the same as current password']);
        exit;
    }

    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("UPDATE tbl_contractor SET contractor_password = ? WHERE contractor_id =?");
    $stmt->bind_param("si", $hashed_password, $contractor_id);

    if($stmt->execute()) {
        echo json_encode(['message' => 'Password successfully updated']);
    } else {
        echo json_encode(['message' => 'Failed to update password']);
    }
} catch (Exception $e) {
    echo json_encode(['message' => 'error', 'error' => 'Something went wrong']);
}
?>