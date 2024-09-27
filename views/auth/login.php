<?php
// Include configuration file and start session
include '../../includes/config.php';
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize user input
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if the user exists
    $query = "SELECT * FROM User WHERE Email='$email'";
    $result = mysqli_query($conn, $query);

    // Handle query execution error
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    // If user exists
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Verify password (without hashing)
        if ($password === $user['password']) {
            // Set session variables
            $_SESSION['user_id'] = $user['UserID'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['Role'];

            // Update LastLogin
            $updateQuery = "UPDATE User SET LastLogin = NOW() WHERE UserID = " . $user['UserID'];
            $updateResult = mysqli_query($conn, $updateQuery);

            // Handle update query execution error
            if (!$updateResult) {
                die("Update query failed: " . mysqli_error($conn));
            }

            // Redirect based on role
            if ($user['Role'] == 'Admin') {
                $redirect_url = $base_url . "/views/admin/admin_profile.php";
            } else {
                $redirect_url = $base_url . "/views/user/profile.php";
            }

            header("Location: $redirect_url");
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Jonogoner Kotha</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <style>
        body {
            background-image: url('<?php echo $base_url; ?>/assets/images/image.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 100px;
        }

        .login-container {
            transform: scale(.75);
            transition: transform 0.5s ease, background-color 2s ease, box-shadow 1s ease;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 15px;
            border-radius: 10px;
            width: 90%;
            max-width: 450px;
        }

        .login-container:hover {
            transform: scale(.80);
            background-image: linear-gradient(135deg, green, red);
            box-shadow: 0 0 20px 10px rgba(0, 255, 0, 0.7);
        }

        .login-container:hover h1,
        .login-container:hover label,
        .login-container:hover p,
        .login-container:hover a {
            color: white;
        }

        .login-container input {
            transition: all 0.3s ease;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php include '../templates/header.php'; ?>

    <!-- Login Form Container -->
    <div class="login-container">
        <h1 class="text-2xl font-bold text-green-700 text-center mb-6">Login to Your Account</h1>

        <?php if (isset($error)): ?>
            <p class="text-red-500 text-center mb-4"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="<?php echo $base_url; ?>/views/auth/login.php" method="POST">
            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter your email" required>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter your password" required>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center mb-4">
                <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                <label for="remember" class="ml-2 block text-gray-700">Remember me</label>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-500 transition duration-300">Login</button>
            </div>
        </form>

        <!-- Forgot Password -->
        <p class="text-center text-gray-600 mt-4">
            <a href="<?php echo $base_url; ?>/views/auth/forgot_password.php" class="text-green-600 hover:text-green-500">Forgot Password?</a>
        </p>

        <!-- Don't have an account -->
        <p class="text-center text-gray-600 mt-6">Don't have an account? <a href="<?php echo $base_url; ?>/views/auth/register.php" class="text-green-600 hover:text-green-500">Register</a></p>
    </div>

</body>

</html>