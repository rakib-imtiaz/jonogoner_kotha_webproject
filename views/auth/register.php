<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Jonogoner Kotha</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <style>
        body {
            background-image: url('/assets/images/image.png'); /* Replace with the path to your background image */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 100px;
        }

        .register-container {
            transform: scale(.75);
            transition: transform 0.5s ease, background-color 2s ease, box-shadow 1s ease;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 15px;
            border-radius: 10px;
            width: 90%;
            max-width: 450px; /* Reduced size */
        }

        .register-container:hover {
            transform: scale(.80);
            background-image: linear-gradient(135deg, green, red); /* Bangladesh flag gradient */
            box-shadow: 0 0 20px 10px rgba(0, 255, 0, 0.7);
        }

        .register-container:hover h1,
        .register-container:hover label,
        .register-container:hover p,
        .register-container:hover a {
            color: white;
        }

        .register-container input {
            transition: all 0.3s ease;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php include '../templates/header.php'; ?>

    <!-- Registration Form Container -->
    <div class="register-container">
        <h1 class="text-2xl font-bold text-green-700 text-center mb-6">Create Your Account</h1>

        <form action="process_registration.php" method="POST">
            <!-- Full Name -->
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">Full Name</label>
                <input type="text" id="name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter your full name" required>
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter your email" required>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Create a password" required>
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="confirm_password" class="block text-gray-700 font-bold mb-2">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Confirm your password" required>
            </div>

            <!-- Terms and Conditions -->
            <div class="flex items-center mb-4">
                <input type="checkbox" id="terms" name="terms" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                <label for="terms" class="ml-2 block text-gray-700">I agree to the <a href="#" class="text-green-600 hover:text-green-500">Terms and Conditions</a></label>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-500 transition duration-300">Create Account</button>
            </div>
        </form>

        <!-- Already have an account -->
        <p class="text-center text-gray-600 mt-6">Already have an account? <a href="login.php" class="text-green-600 hover:text-green-500">Log in</a></p>
    </div>

</body>

</html>
