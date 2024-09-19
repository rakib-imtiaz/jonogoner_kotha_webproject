<?php
session_start();
include '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $role = 'User';  // Default role as 'User'

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
      

        // Check if email or username already exists
        $check_user_query = "SELECT * FROM User WHERE Email='$email' OR Username='$username'";
        $result = mysqli_query($conn, $check_user_query);

        if (mysqli_num_rows($result) > 0) {
            $error = "Username or email already exists!";
        } else {
            // Insert user into the database
            $query = "INSERT INTO User (Username, password, Email, Role, DateRegistered) 
                      VALUES ('$username', '$password', '$email', '$role', NOW())";
            if (mysqli_query($conn, $query)) {
                $_SESSION['user'] = $username;
                header("Location: profile.php");
                exit();
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>