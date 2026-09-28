<?php  
session_start();
header('Content-Type: application/json');
include_once '../config/db.php';

if(!isset($_SESSION['admin_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in as admin']);
    exit;
}

$admin_id = $_SESSION['admin_id'];
$current_password = $_POST['current_admin_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_new_password'] ?? '';

if(empty($current_password) || empty($new_password) ||empty($confirm_password)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit;
}

if ($new_password !== $confirm_password) {
    echo json_encode(['status' => 'error', 'message' => 'Password does not match']);
    exit;
}

if(strlen($new_password) <8) {
    echo json_encode(['status' => 'error', 'message' => 'Password must be at least 8 characters long']);
    exit;
}

try {
    $stmt = $conn->prepare('SELECT admin_password FROM tbl_admin WHERE admin_id = ?');
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin =$result->fetch_assoc();

    if(!$admin) {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
        exit;
    }

    if (!password_verify($current_password, $admin['admin_password'])) {
        echo json_encode(['status' => 'error', 'message' => 'Current password is incorrect']);
        exit;
    }

    if (password_verify($new_password, $admin['admin_password'])) {
        echo json_encode(['status' => 'error', 'message' => 'New password cannot be the same as current password']);
        exit;
    }

    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("UPDATE tbl_admin SET admin_password = ? WHERE admin_id =?");
    $stmt->bind_param("si", $hashed_password, $admin_id);

    if($stmt->execute()) {
       echo json_encode(['status' => 'success', 'message' => 'Password updated successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update password']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Something went wrong']);
}
?>