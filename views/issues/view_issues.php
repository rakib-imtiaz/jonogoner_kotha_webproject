<?php
// Start the session and include the configuration file
session_start();
include '../../includes/config.php';

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user']);

// Fetch all issues from the database
$query = "SELECT Issue.Title, Issue.Description, Issue.Location, Issue.DateSubmitted, User.Username, Category.Name AS CategoryName
          FROM Issue 
          JOIN User ON Issue.SubmittedBy = User.UserID
          JOIN Category ON Issue.CategoryID = Category.CategoryID";

// Execute the query
$result = mysqli_query($conn, $query);

// Check if the query execution was successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Issues - Jonogoner Kotha</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">

    <!-- Include the header template -->
    <?php include '../../views/templates/header.php'; ?>
    <style>
        /* Commented out background image styles */
        /* body {
            background-image: url('/assets/images/report_issues.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
           
        } */
    </style>

    <!-- View Issues Section -->
    <div style="transform:scale(0.9);" class="container mx-auto py-16">
        <h1 class="text-3xl font-bold text-red-700 mb-6">Reported Issues</h1>

        <div style="opacity: 0.9;" class="bg-white p-8 rounded-lg shadow-lg">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <ul class="space-y-4">
                    <?php while ($issue = mysqli_fetch_assoc($result)): ?>
                        <li class="border bg-gray-100 p-4 rounded-lg shadow">
                            <h2 class="text-xl font-bold"><?php echo $issue['Title']; ?></h2>
                            <p class="text-gray-600"><?php echo $issue['Description']; ?></p>
                            <p class="text-sm text-gray-500 mt-2">Category: <?php echo $issue['CategoryName']; ?> | Location: <?php echo $issue['Location']; ?></p>
                            <p class="text-sm text-gray-500">Submitted by: <?php echo $issue['Username']; ?> on <?php echo date('Y-m-d', strtotime($issue['DateSubmitted'])); ?></p>
                            <?php if ($isLoggedIn): ?>
                                <!-- Display interaction options for logged-in users -->
                                <button class="bg-green-600 text-white py-2 px-4 rounded-lg">Comment</button>
                                <button class="bg-green-600 text-white py-2 px-4 rounded-lg">Propose Solution</button>
                            <?php else: ?>
                                <!-- Display message for non-logged-in users -->
                                <p class="text-sm text-red-500 italic">Log in to interact with issues.</p>
                            <?php endif; ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No issues reported yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Include the footer template -->
    <?php include '../../views/templates/footer.php'; ?>

</body>
</html>
