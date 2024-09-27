<?php
session_start();
include '../../includes/config.php';

// Check if the user is logged in and has the 'User' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'User') {
    header("Location: " . $base_url . "/views/auth/login.php");
    exit();
}

// Fetch user information from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM User WHERE UserID = '$user_id'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
} else {
    // Handle error if user data couldn't be retrieved
    die("User data could not be retrieved.");
}

// Handle form submission for profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_name = mysqli_real_escape_string($conn, $_POST['name']);
    $new_email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_password = !empty($_POST['password']) ? $_POST['password'] : $user_data['password'];

    $update_query = "UPDATE User SET username = '$new_name', Email = '$new_email', password = '$new_password' WHERE UserID = '$user_id'";

    if (mysqli_query($conn, $update_query)) {
        $_SESSION['username'] = $new_name; // Update session username
        header("Location: " . $base_url . "/views/user/profile.php");
        exit();
    } else {
        // Handle error if profile update fails
        $error_message = "Error updating profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Jonogoner Kotha</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">

    <!-- Include navbar -->
    <?php include '../../templates/navbar.php'; ?>

    <!-- Edit Profile Form -->
    <div class="container mx-auto py-16">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md mx-auto">
            <h1 class="text-3xl font-bold text-green-700 mb-6">Edit Your Profile</h1>

            <?php if (isset($error_message)): ?>
                <p class="text-red-500 mb-4"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <form action="<?php echo $base_url; ?>/views/user/edit_profile.php" method="POST">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">Full Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user_data['username']); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['Email']); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700">Password (Leave blank to keep current password)</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-500">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Include footer -->
    <?php include '../../templates/footer.php'; ?>

</body>
</html>
