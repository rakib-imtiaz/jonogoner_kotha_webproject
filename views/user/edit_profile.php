<?php
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Fetch user information
$username = $_SESSION['user'];
$query = "SELECT * FROM User WHERE Username = '$username'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
} else {
    echo "User data could not be retrieved.";
}

// Update profile if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_name = mysqli_real_escape_string($conn, $_POST['name']);
    $new_email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $user_data['PasswordHash'];

    $update_query = "UPDATE User SET Username = '$new_name', Email = '$new_email', PasswordHash = '$new_password' WHERE Username = '$username'";

    if (mysqli_query($conn, $update_query)) {
        $_SESSION['user'] = $new_name; // Update session username
        echo "Profile updated successfully!";
        header("Location: profile.php");
    } else {
        echo "Error updating profile.";
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

    <!-- Navbar -->
    <?php include 'templates/navbar.php'; ?>

    <!-- Edit Profile Form -->
    <div class="container mx-auto py-16">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md mx-auto">
            <h1 class="text-3xl font-bold text-green-700 mb-6">Edit Your Profile</h1>

            <form action="edit_profile.php" method="POST">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">Full Name</label>
                    <input type="text" id="name" name="name" value="<?php echo $user_data['Username']; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo $user_data['Email']; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700">Password (Leave blank to keep current password)</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-500">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'templates/footer.php'; ?>

</body>
</html>
