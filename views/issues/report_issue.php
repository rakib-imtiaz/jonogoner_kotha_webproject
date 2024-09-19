<?php
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Process the issue report form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $issue_title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $submitted_by = $_SESSION['user'];  // Get username from session

    $insert_query = "INSERT INTO Issue (Title, Description, CategoryID, Location, DateSubmitted, Status, SubmittedBy) 
                     VALUES ('$issue_title', '$description', '$category_id', '$location', NOW(), 'Open', (SELECT UserID FROM User WHERE Username = '$submitted_by'))";

    if (mysqli_query($conn, $insert_query)) {
        echo "Issue reported successfully!";
    } else {
        echo "Error reporting issue.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report an Issue - Jonogoner Kotha</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">

    <!-- Navbar -->
    <?php include 'templates/navbar.php'; ?>

    <!-- Report an Issue Form -->
    <div class="container mx-auto py-16">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md mx-auto">
            <h1 class="text-3xl font-bold text-green-700 mb-6">Report an Issue</h1>

            <form action="report_issue.php" method="POST">
                <div class="mb-4">
                    <label for="title" class="block text-gray-700">Issue Title</label>
                    <input type="text" id="title" name="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700">Description</label>
                    <textarea id="description" name="description" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="category" class="block text-gray-700">Category</label>
                    <select id="category" name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
                        <option value="1">Infrastructure</option>
                        <option value="2">Corruption</option>
                        <option value="3">Public Services</option>
                        <option value="4">Environment</option>
                        <option value="5">Health</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="location" class="block text-gray-700">Location</label>
                    <input type="text" id="location" name="location" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
                </div>
                <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-500">Submit Issue</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'templates/footer.php'; ?>

</body>
</html>
