<?php
session_start();
include '../config/db.php';

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Unknown error occurred.'];

if($_SERVER ['REQUEST_METHOD'] == 'POST'){
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    //https://www.w3schools.com/php/php_form_url_email.asp = FILTERING EMAIL
    //https://www.w3schools.com/php/func_string_strlen.asp = PASSWORD VALIDATION

    if(empty($email) || empty($password)) {
        $response['status'] = 'error';
        $response['message'] = "Email and password are required!";
    } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $response['status']  = 'error';
        $response['message'] = "Invalid email address!";
    }  else if(strlen($password) < 8) {
        $response['status'] = 'error';
        $response['message'] = "Password must be at least 8 characters long!";
    } else {
        $stmt = $conn->prepare("SELECT admin_id, admin_name, admin_password FROM tbl_admin WHERE admin_email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($admin_id, $admin_name, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['admin_id']   = $admin_id;
                $_SESSION['admin_name'] = $admin_name;
                $_SESSION['user_type']  = 'admin';
                $_SESSION['logged_in']  = true;

                $response['status']     = 'success';
                $response['message']    = "Admin login successful!";
                $response['redirect']   = 'admin_dashboard.php';
                $response['type']       = 'admin';
            } else {
                $response['status']     = 'error';
                $response['message']    = 'Invalid Password!';
            }
        } else {
           $stmt = $conn->prepare("SELECT contractor_id, contractor_name, contractor_password FROM tbl_contractor WHERE contractor_email = ?");
           $stmt->bind_param("s", $email);
           $stmt->execute();
           $stmt->store_result();

           if($stmt->num_rows > 0) {
                $stmt->bind_result($contractor_id, $contractor_name, $hashed_password);
                $stmt->fetch();

                if (password_verify($password,  $hashed_password)) {
                    $_SESSION['contractor_id'] = $contractor_id;
                    $_SESSION['contractor_name'] = $contractor_name;
                    $_SESSION['user_type'] = 'contractor';
                    $_SESSION['logged_in'] = true;

                    $response['status'] = 'success';
                    $response['message'] = 'Contractor login successful!';
                    $response['redirect'] = 'contractor_dashboard.php';
                    $response['type'] = 'contractor';
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Invalid Password!';
                }
            } else {
                $response['message'] = "User not found!";
            }
        }
        $stmt->close();
    }
    echo json_encode($response);
}
$conn->close();
?>
