<?php
session_start();

// Destroy all session variables
$_SESSION = [];

// Destroy the session itself
session_destroy();

// Redirect to admin login page
header("Location: adminLogin.php");
exit;
?>