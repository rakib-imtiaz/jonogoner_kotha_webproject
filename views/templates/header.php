<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Jonogoner Kotha</title>
    <!-- Meta Tag for Responsive Design -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        .nav-container {
            transition: transform 0.5s ease, background-color 2s ease, box-shadow 1s ease;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 50;
        }

        .nav-container.hide {
            transform: translateY(-100%);
        }

        .nav-container:hover {
            /* transform: scale(1.05); */
            background-image: linear-gradient(135deg, green, red);
            background-color: transparent;
            box-shadow: 0 0 20px 10px rgba(0, 255, 0, 0.7);
        }

        .nav-container:hover a,
        .nav-container:hover span {
            color: white;
        }

        .nav-container ul li a:hover {
            transform: scale(1.1);
        }

        /* Mobile menu styling */
        #mobile-menu {
            background-color: white;
        }

        #mobile-menu a {
            color: black;
        }

        #mobile-menu a:hover {
            color: black;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800">
    <!-- Header and Navbar -->
    <header class="nav-container">
        <nav class="container mx-auto flex items-center justify-between p-4">
            <!-- Logo -->
            <a href="/index.php" class="flex items-center">
                <img src="/assets/images/logo2.png" alt="Jonogoner Kotha Logo" class="h-10 w-10 mr-2">
                <span id="title" class="text-2xl font-bold text-green-700">Jonogoner Kotha</span>
            </a>

            <!-- Navigation Links -->
            <ul class="hidden md:flex space-x-6">
                <li><a href="/views/issues/view_issues.php" class="text-gray-600 hover:text-green-700">Issues</a></li>
                <li><a href="/views/poll/polls.php" class="text-gray-600 hover:text-green-700">Live Polls</a></li>
                <li><a href="/views/emergency/emergency.php" class="text-gray-600 hover:text-green-700">Emergency</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="profile.php" class="text-gray-600 hover:text-green-700"><?php echo $_SESSION['username']; ?></a></li>
                    <li><a href="logout.php" class="text-gray-600 hover:text-green-700">Logout</a></li>
                <?php else: ?>
                    <li><a href="<?php echo '/views/auth/login.php'; ?>" class="text-gray-600 hover:text-green-700">Login</a></li>
                    <li><a href="<?php echo '/views/auth/register.php'; ?>" class="text-gray-600 hover:text-green-700">Register</a></li>
                <?php endif; ?>
            </ul>

            <!-- Mobile Menu Icon -->
            <div class="md:hidden">
                <button id="menu-toggle" class="text-gray-600 hover:text-green-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </div>
        </nav>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden bg-white shadow-md md:hidden">
            <ul class="space-y-4 p-4">
                <li><a href="/views/issues/view_issues.php" class="block text-gray-600 hover:text-green-700">Issues</a></li>
                <li><a href="/views/issues/solutions.php" class="block text-gray-600 hover:text-green-700">Solutions</a></li>
                <li><a href="/views/poll/polls.php" class="block text-gray-600 hover:text-green-700">Live Polls</a></li>
                <li><a href="/views/emergency/emergency.php" class="block text-gray-600 hover:text-green-700">Emergency</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="profile.php" class="block text-gray-600 hover:text-green-700"><?php echo $_SESSION['username']; ?></a></li>
                    <li><a href="logout.php" class="block text-gray-600 hover:text-green-700">Logout</a></li>
                <?php else: ?>
                    <li><a href="/views/auth/login.php" class="block text-gray-600 hover:text-green-700">Login</a></li>
                    <li><a href="/views/auth/register.php" class="block text-gray-600 hover:text-green-700">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </header>

    <!-- Script to toggle the mobile menu -->
    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

        menuToggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Hide and show header on scroll
        let lastScroll = 0;
        let header = document.querySelector('.nav-container');
        let scrollTimeout;

        window.addEventListener('scroll', () => {
            clearTimeout(scrollTimeout);
            
            let currentScroll = window.pageYOffset;

            if (currentScroll > lastScroll) {
                header.classList.add('hide'); // Hide on scroll down
            } else {
                header.classList.remove('hide'); // Show on scroll up
            }

            lastScroll = currentScroll;

            // After 2 seconds of no scrolling, show the header again
            scrollTimeout = setTimeout(() => {
                header.classList.remove('hide');
            }, 2000);
        });
    </script>
</body>

</html>
