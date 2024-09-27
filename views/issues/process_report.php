<?php
// Start the session and include the configuration file
session_start();
include '../../includes/config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: " . $base_url . "/views/auth/login.php");
    exit();
}

// Process the issue report form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and retrieve form data
    $issue_title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $submitted_by = $_SESSION['user_id'];  // Get user ID from session

    // Prepare the SQL query to insert the issue into the database
    $insert_query = "INSERT INTO Issue (Title, Description, CategoryID, Location, DateSubmitted, Status, SubmittedBy) 
                     VALUES ('$issue_title', '$description', '$category_id', '$location', NOW(), 'Open', '$submitted_by')";

    // Execute the query and check for success
    if (mysqli_query($conn, $insert_query)) {
        // Display success message and redirect to profile page
        echo "
        <div id='spinner' class='fixed top-0 left-0 w-full h-screen bg-gray-500 bg-opacity-50 flex justify-center items-center'>
            <div class='bg-white p-4 rounded-lg shadow-lg text-center'>
                <img src='" . $base_url . "/assets/images/process.gif' alt='Loading' class='w-32 h-32 mb-4'>
                <h1 class='text-2xl font-bold'>Processing Report... Please wait</h1>
            </div>
        </div>
        <script>
            // After 3 seconds, update the spinner content
            setTimeout(function() {
                document.getElementById('spinner').innerHTML = `
                    <div class='bg-white p-4 rounded-lg shadow-lg text-center'>
                        <h1 class='text-2xl font-bold'>Issue reported successfully!</h1>
                        <p class='text-center'>Redirecting to Profile...</p>
                        <div class='h-4 w-full bg-gray-300 rounded-full'>
                            <div class='h-4 bg-green-500 rounded-full' style='width: 100%; animation: load 3s ease-in-out forwards;'></div>
                        </div>
                    </div>`;
                // After another 3 seconds, redirect to the profile page
                setTimeout(function() {
                    window.location.href = '" . $base_url . "/views/user/profile.php';
                }, 3000);
            }, 3000);
        </script>
        ";
    } else {
        // Display error message if the query fails
        echo "Error reporting issue: " . mysqli_error($conn);
    }
}
?>
