<?php
// Include database configuration
include '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.location.href='" . $base_url . "/views/auth/register.php';</script>";
        exit();
    }

    // Check if email already exists
    $check_email = "SELECT * FROM User WHERE Email='$email'";
    $result = mysqli_query($conn, $check_email);
    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Email already exists!'); window.location.href='" . $base_url . "/views/auth/register.php';</script>";
        exit();
    }

    // Generate username from name (you might want to add logic to ensure uniqueness)
    $username = strtolower(str_replace(' ', '_', $name));

    // Insert user into database
    $insert_query = "INSERT INTO User (username, password, Email, Role, DateRegistered) 
                     VALUES ('$username', '$password', '$email', 'User', NOW())";

    if (mysqli_query($conn, $insert_query)) {
        // Display success message and redirect to login page
        echo "
        <div id='spinner' class='fixed inset-0 flex justify-center items-center bg-gray-500 bg-opacity-50'>
            <div class='bg-white p-6 rounded-lg shadow-lg text-center'>
                <div class='loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-24 w-24 mb-4'></div>
                <h1 class='text-2xl font-bold'>Processing Registration... Please wait</h1>
            </div>
        </div>
        <script>
            setTimeout(function() {
                document.getElementById('spinner').innerHTML = `
                    <div class='bg-white p-4 rounded-lg shadow-lg text-center'>
                        <h1 class='text-2xl font-bold'>Registration successful!</h1>
                        <p class='text-center'>Redirecting to Login...</p>
                        <div class='h-4 w-full bg-gray-300 rounded-full'>
                            <div class='h-4 bg-green-500 rounded-full' style='width: 100%; animation: load 3s ease-in-out forwards;'></div>
                        </div>
                    </div>`;
                setTimeout(function() {
                    window.location.href = '" . $base_url . "/views/auth/login.php';
                }, 3000);
            }, 3000);
        </script>
        ";
    } else {
        // Display error message if registration fails
        echo "
        <div id='error' class='fixed inset-0 flex justify-center items-center bg-gray-500 bg-opacity-50'>
            <div class='bg-white p-6 rounded-lg shadow-lg text-center'>
                <h1 class='text-2xl font-bold text-red-500'>Error During Registration</h1>
                <p class='mt-2'>An error occurred while registering. Please try again later.</p>
                <button onclick='window.history.back()' class='mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded'>
                    Go Back
                </button>
            </div>
        </div>
        ";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Processing - Jonogoner Kotha</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .loader {
            border-top-color: #3498db;
            animation: spin 1s ease-in-out infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Content is dynamically inserted by JavaScript -->
</body>
</html>