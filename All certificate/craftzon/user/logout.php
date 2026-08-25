<?php
session_start();

// Unset all session variables
session_unset();

// Destroy the session entirely
session_destroy();

// Redirect to login page
header("Location: logincraft.php");
exit();
?>
