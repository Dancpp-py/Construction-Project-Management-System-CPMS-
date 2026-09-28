<?php
if (!isset($_SESSION['admin_id'])) {
    header("Location: /CPMS/login_form.php");
    exit;
}
?>
