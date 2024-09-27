<?php
// Start the session and include the configuration file
session_start();
include '../../includes/config.php';

// Fetch emergency reports from the database, ordered by submission date (most recent first)
$query = "SELECT Title, Description, Type, Location, DateSubmitted FROM EmergencyPost ORDER BY DateSubmitted DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency - Jonogoner Kotha</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Add hover effect to emergency containers */
        .emergency-container:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease;
        }
    </style>
</head>
<style>
    /* Set background image and styles for the body */
    body {
        background-image: url('/assets/images/emergency_post.png');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        background-repeat: no-repeat;
        background-color: #f4f4f4;
    }
</style>

<body class="bg-gray-100">
    <!-- Include the header/navbar -->
    <?php include '../templates/header.php'; ?>

    <!-- Main Content Container -->
    <div class="container mx-auto py-16 px-6">
        <h1 class="text-4xl font-bold text-green-700 mb-10 text-center">Emergency Reports</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($emergency = mysqli_fetch_assoc($result)): ?>
                    <!-- Display each emergency report -->
                    <div style="opacity: 0.9;" class="emergency-container bg-white rounded-lg shadow-lg p-6 transition-transform">
                        <h2 class="text-2xl font-bold text-red-600 mb-2"><?php echo $emergency['Title']; ?></h2>
                        <p class="text-gray-700 mb-4"><?php echo $emergency['Description']; ?></p>
                        <p class="text-gray-500 mb-2">Type: <?php echo $emergency['Type']; ?></p>
                        <p class="text-gray-500 mb-2">Location: <?php echo $emergency['Location']; ?></p>
                        <p class="text-gray-500">Date: <?php echo date('Y-m-d', strtotime($emergency['DateSubmitted'])); ?></p>
                        <br>
                        <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full" onclick="copyLinkToClipboard(this, '<?php echo $_SERVER['REQUEST_URI'] . '?id=' . $emergency['EmergencyPostID']; ?>')">Share post</button>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <!-- Display message if no emergency reports are available -->
                <p class="text-gray-700 text-center col-span-full">No emergency reports available.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Function to copy the post link to clipboard
        function copyLinkToClipboard(element, link) {
            navigator.clipboard.writeText(link);
            element.innerText = "Copied!";
            setTimeout(() => {
                element.innerText = "Share post";
            }, 2000);
        }
    </script>

    <!-- Include the footer -->
    <?php include '../templates/footer.php'; ?>
</body>

</html>
