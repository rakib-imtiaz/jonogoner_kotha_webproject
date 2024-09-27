<?php
// Include configuration file
include '../../includes/config.php';

// Start the session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: " . $base_url . "/views/auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report an Issue - Jonogoner Kotha</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Spinner Styles */
        .loader {
            border-top-color: #3498db;
            animation: spin 1.5s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800">

    <!-- Include header/navbar -->
    <?php include $base_url . '/views/templates/header.php'; ?>

    <!-- Report an Issue Form -->
    <div style="transform: scale(.90);" class="container mx-auto py-16">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md mx-auto">
            <h1 class="text-3xl font-bold text-green-700 mb-6">Report an Issue</h1>

            <form action="<?php echo $base_url; ?>/views/issues/process_report.php" method="POST">
                <!-- Issue Title Input -->
                <div class="mb-4">
                    <label for="title" class="block text-gray-700">Issue Title</label>
                    <input type="text" id="title" name="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
                </div>
                <!-- Description Textarea -->
                <div class="mb-4">
                    <label for="description" class="block text-gray-700">Description</label>
                    <textarea id="description" name="description" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required></textarea>
                </div>
                <!-- Category Dropdown -->
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
                <!-- Location Input -->
                <div class="mb-4">
                    <label for="location" class="block text-gray-700">Location</label>
                    <input type="text" id="location" name="location" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
                </div>
                <!-- Submit Button -->
                <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-500">Submit Issue</button>
            </form>
        </div>
    </div>

    <!-- Include footer -->
    <?php include $base_url . '/views/templates/footer.php'; ?>

</body>

</html>
