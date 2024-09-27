<?php
// Start the session and include the configuration file
session_start();
include '../../includes/config.php';

// Check if the request method is POST, the user is logged in, and has Admin role
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id']) && $_SESSION['role'] === 'Admin') {
    // Sanitize the user ID input
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id_delete']);

    // Prepare and execute the delete query
    $delete_query = "DELETE FROM User WHERE UserID = $user_id";
    if (mysqli_query($conn, $delete_query)) {
        // Redirect to admin profile page with success message
        header("Location: admin_profile.php?success=2");
    } else {
        // Redirect to admin profile page with error message
        header("Location: admin_profile.php?error=2");
    }
} else {
    // Redirect to login page if conditions are not met
    header("Location: " . $base_url . "/views/auth/login.php");
}

// End the script execution
exit();
