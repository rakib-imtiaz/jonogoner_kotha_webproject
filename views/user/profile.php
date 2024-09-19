<?php
// Start session
session_start();
include '../../includes/config.php';  // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");  // Redirect to login page if the user is not logged in
    exit();
}

// Fetch user information from the database
$email = $_SESSION['user'];
$query = "SELECT * FROM User WHERE email = '$email'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);  // Fetch user details
} else {
    echo "User data could not be retrieved.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Jonogoner Kotha</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Custom CSS for Sliding Sidebar and Modal -->
    <style>
        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .sidebar.open {
            transform: translateX(0);
        }

        .main-content {
            transition: transform 0.3s ease;
        }

        .main-content.slide-right {
            transform: translateX(250px);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
        }

        .close-btn {
            float: right;
            font-size: 24px;
            cursor: pointer;
        }

        .modal.show {
            display: block;
        }

        .tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .tabs button {
            background-color: #f4f4f4;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .tabs button:hover {
            background-color: #ddd;
        }

        .tabs button.active {
            background-color: #006400;
            color: white;
        }

        .content {
            display: none;
        }

        .content.active {
            display: block;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800">

    <!-- Navbar -->
    <?php include 'templates/navbar.php'; ?>

    <!-- Sidebar (Hidden initially, slides in when menu is toggled) -->
    <div id="sidebar" class="fixed top-0 left-0 w-64 h-full bg-white shadow-lg sidebar z-50">
        <div class="p-6 bg-green-700 text-white">
            <!-- Profile Picture and Name -->
            <div class="text-center mb-4">
                <img src="/assets/images/<?php echo $user_data['ProfilePicture']; ?>" alt="Profile Picture" class="w-24 h-24 rounded-full mx-auto border-4 border-white shadow-md">
                <form action="upload_profile_pic.php" method="POST" enctype="multipart/form-data">
                    <input type="file" name="profile_pic" accept="image/*" class="mt-2">
                    <button type="submit" class="bg-green-500 text-white py-1 px-3 mt-2 rounded">Upload New Picture</button>
                </form>
                <h2 class="mt-2 text-xl font-bold"><?php echo $user_data['Username']; ?></h2>
                <p class="text-sm text-green-200">Member since <?php echo date('Y', strtotime($user_data['DateRegistered'])); ?></p>
            </div>
        </div>
        <ul class="p-6 space-y-4">
            <li><a href="#" class="block text-gray-700 hover:text-green-700">Edit Profile</a></li>
            <li><a href="#" class="block text-gray-700 hover:text-green-700">Privacy Settings</a></li>
            <li><a href="#" class="block text-gray-700 hover:text-green-700">Notification Settings</a></li>
            <li><a href="#" class="block text-gray-700 hover:text-green-700">Account Security</a></li>
            <li><a href="../issues/report_issue.php" class="block text-gray-700 hover:text-green-700">Report an Issue</a></li>
            <li><a href="../issues/view_issue.php" class="block text-gray-700 hover:text-green-700">View Issues</a></li>
            <li><a href="../issues/propose_solution.php" class="block text-gray-700 hover:text-green-700">Propose a Solution</a></li>
            <li><a href="logout.php" class="block text-gray-700 hover:text-green-700">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div id="mainContent" class="main-content pl-0">
        <!-- Hamburger Icon for Sidebar -->
        <div class="p-4 bg-green-600 text-white">
            <button id="menu-toggle" class="text-white focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
            <h1 class="inline-block ml-4 text-2xl font-bold">Your Feed</h1>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <button id="myTimelineBtn" class="active">My Time Line</button>
            <button id="globalTimelineBtn">Global Time Line</button>
        </div>

        <!-- My Timeline Content -->
        <div id="myTimeline" class="content active">
            <!-- Fetch and display user-specific posts with edit option -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold text-green-700">Your Recent Activities</h2>
                <ul class="mt-4 space-y-4">
                    <!-- Loop through user's personal posts here -->
                    <li class="p-4 bg-gray-50 rounded-md shadow">
                        <h3 class="text-lg font-semibold">Post Title 1</h3>
                        <p class="mt-2 text-gray-600">Your content goes here...</p>
                        <button class="bg-blue-500 text-white py-1 px-2 rounded mt-2">Edit Post</button>
                    </li>
                    <li class="p-4 bg-gray-50 rounded-md shadow">
                        <h3 class="text-lg font-semibold">Post Title 2</h3>
                        <p class="mt-2 text-gray-600">Your content goes here...</p>
                        <button class="bg-blue-500 text-white py-1 px-2 rounded mt-2">Edit Post</button>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Global Timeline Content -->
        <div id="globalTimeline" class="content">
            <!-- Fetch and display global posts in view-only mode -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold text-green-700">Global Recent Activities</h2>
                <ul class="mt-4 space-y-4">
                    <!-- Loop through global posts here -->
                    <li class="p-4 bg-gray-50 rounded-md shadow">
                        <h3 class="text-lg font-semibold">Global Post Title 1</h3>
                        <p class="mt-2 text-gray-600">Global content goes here...</p>
                    </li>
                    <li class="p-4 bg-gray-50 rounded-md shadow">
                        <h3 class="text-lg font-semibold">Global Post Title 2</h3>
                        <p class="mt-2 text-gray-600">Global content goes here...</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Create Poll Modal -->
    <div id="pollModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2 class="text-2xl font-bold text-green-700 mb-4
            <h2 class=" text-2xl font-bold text-green-700 mb-4">Create a New Poll</h2>
            <form action="create_poll.php" method="POST">
                <div class="mb-4">
                    <label for="poll_question" class="block text-gray-700">Poll Question</label>
                    <input type="text" id="poll_question" name="poll_question" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Enter your poll question" required>
                </div>
                <div class="mb-4">
                    <label for="poll_option_1" class="block text-gray-700">Option 1</label>
                    <input type="text" id="poll_option_1" name="poll_option_1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Enter option 1" required>
                </div>
                <div class="mb-4">
                    <label for="poll_option_2" class="block text-gray-700">Option 2</label>
                    <input type="text" id="poll_option_2" name="poll_option_2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Enter option 2" required>
                </div>
                <div class="mb-4">
                    <label for="poll_option_3" class="block text-gray-700">Option 3 (Optional)</label>
                    <input type="text" id="poll_option_3" name="poll_option_3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Enter option 3">
                </div>
                <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-500 transition duration-300">Create Poll</button>
            </form>
        </div>
    </div>

    <!-- Scripts for sidebar and modal -->
    <script>
        // Sidebar toggle
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('mainContent').classList.toggle('slide-right');
        });

        // Modal toggle
        var modal = document.getElementById('pollModal');
        var createPollBtn = document.getElementById('createPollBtn');
        var closeBtn = document.getElementsByClassName('close-btn')[0];

        createPollBtn.onclick = function() {
            modal.classList.add('show');
        }

        closeBtn.onclick = function() {
            modal.classList.remove('show');
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.classList.remove('show');
            }
        }

        // Tab functionality
        document.getElementById('myTimelineBtn').addEventListener('click', function() {
            document.getElementById('myTimeline').classList.add('active');
            document.getElementById('globalTimeline').classList.remove('active');
            this.classList.add('active');
            document.getElementById('globalTimelineBtn').classList.remove('active');
        });

        document.getElementById('globalTimelineBtn').addEventListener('click', function() {
            document.getElementById('globalTimeline').classList.add('active');
            document.getElementById('myTimeline').classList.remove('active');
            this.classList.add('active');
            document.getElementById('myTimelineBtn').classList.remove('active');
        });
    </script>
</body>

</html>