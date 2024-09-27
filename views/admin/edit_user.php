<?php
// Start the session
session_start();

// Include the configuration file
include '../../includes/config.php';

// Check if the request method is POST, user is logged in, and has Admin role
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id']) && $_SESSION['role'] === 'Admin') {
    // Sanitize input data
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $new_email = mysqli_real_escape_string($conn, $_POST['new_email']);

    // Prepare and execute the update query
    $update_query = "UPDATE User SET Email = '$new_email' WHERE UserID = $user_id";
    if (mysqli_query($conn, $update_query)) {
        // Redirect to admin profile page on success
        header("Location: admin_profile.php?success=1");
    } else {
        // Redirect to admin profile page on error
        header("Location: admin_profile.php?error=1");
    }
} else {
    // Redirect to login page if conditions are not met
    header("Location: " . $base_url . "/views/auth/login.php");
}

// End the script execution
exit();
