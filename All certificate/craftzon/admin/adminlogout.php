<?php
session_start();

// Unset only the admin session
if(isset($_SESSION["admin_id"])) {
    unset($_SESSION["admin_id"]);
}

// Optionally destroy all sessions
// session_destroy();

// Redirect to admin login page
header("Location: adminlogin.php");
exit;
?>
