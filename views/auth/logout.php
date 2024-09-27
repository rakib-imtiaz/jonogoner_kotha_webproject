<?php
include '../../includes/config.php';
session_start();
session_destroy();  // Destroy all sessions
header("Location: " . $base_url . "/views/auth/login.php");  // Redirect to login page with base URL
exit();
?>
