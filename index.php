<?php
include './includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jonogoner Kotha - Empower the People</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
    <?php include './views/templates/header.php'; ?>

    <!-- Hero Section -->
    <section class="relative bg-cover bg-center" style="background-image: url('assets/images/landingPage_2.png'); height: 80vh;">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="relative container mx-auto text-center text-white flex flex-col justify-center h-full">
            <h1 class="text-5xl font-bold">Raise Your Voice, Shape the Future</h1>
            <p class="mt-4 text-xl">Empowering the people of Bangladesh through civic engagement</p>
            <div class="mt-6">
                <a href="/views/auth/login.php" class="bg-green-600 hover:bg-green-500 text-white py-3 px-6 rounded-lg mr-4">Get Started</a>
                <a href="/views/auth/register.php" class="bg-white text-green-600 hover:text-white hover:bg-green-500 border border-green-600 py-3 px-6 rounded-lg">Join Us</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto text-center">
            <h2 class="text-4xl font-bold text-gray-800">How Jonogoner Kotha Works</h2>
            <p class="mt-4 text-lg text-gray-600">Simple, transparent, and impactful</p>
            <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-gray-100 p-8 rounded-lg shadow-md hover:shadow-lg transition duration-300 ease-in-out transform hover:-translate-y-2">
                    <img src="assets/images/report_issues.png" alt="Report Issues" class="mx-auto h-24 w-24">
                    <h3 class="mt-4 text-2xl font-semibold text-green-600">Report Issues</h3>
                    <p class="mt-2 text-gray-600">Share real-life problems affecting your community, such as corruption, infrastructure failures, and more.</p>
                </div>
                <!-- Feature 2 -->
                <div class="bg-gray-100 p-8 rounded-lg shadow-md hover:shadow-lg transition duration-300 ease-in-out transform hover:-translate-y-2">
                    <img src="assets/images/proposed_solution.png" alt="Propose Solutions" class="mx-auto h-24 w-24">
                    <h3 class="mt-4 text-2xl font-semibold text-green-600">Propose Solutions</h3>
                    <p class="mt-2 text-gray-600">Collaborate with others to find innovative solutions to the issues raised by the community.</p>
                </div>
                <!-- Feature 3 -->
                <div class="bg-gray-100 p-8 rounded-lg shadow-md hover:shadow-lg transition duration-300 ease-in-out transform hover:-translate-y-2">
                    <img src="assets/images/track_progress.png" alt="Track Progress" class="mx-auto h-24 w-24">
                    <h3 class="mt-4 text-2xl font-semibold text-green-600">Track Progress</h3>
                    <p class="mt-2 text-gray-600">Monitor the status of your issues and solutions and hold authorities accountable with our transparency dashboard.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-16 bg-green-600 text-white">
        <div class="container mx-auto text-center">
            <h2 class="text-4xl font-bold">Ready to Make a Change?</h2>
            <p class="mt-4 text-lg">Join thousands of others in making Bangladesh a better place.</p>
            <div class="mt-6">
                <a href="register.php" class="bg-white text-green-600 hover:text-white hover:bg-green-500 border border-white py-3 px-6 rounded-lg">Register Now</a>
            </div>
        </div>
    </section>

    <?php include './views/templates/footer.php'; ?>
</body>
</html>
