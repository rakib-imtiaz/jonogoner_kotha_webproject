<?php
// Start session
session_start();
include '../../includes/config.php';  // Include database connection file

// Check if the user is logged in and has the 'User' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'User') {
    header("Location: " . $base_url . "/views/auth/login.php");  // Redirect to login page if not logged in
    exit();
}

// Fetch user information from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM User WHERE UserID = '$user_id'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);  // Fetch user details
} else {
    echo "User data could not be retrieved.";
    exit();
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
    <script src="<?php echo $base_url; ?>/assets/js/loader.js"></script>
    <!-- Custom CSS -->
    <style>
        /* Sidebar styles */
        .sidebar {
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }

        /* Main content styles */
        .main-content {
            margin-left: 255px;
            z-index: 1;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: static;
                height: auto;
            }
            .main-content {
                margin-left: 0;
            }
        }

        /* Modal styles */
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

        /* Tab styles */
        .tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .tabs button {
            background-color: #f4f4f4;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            border-radius: 5px;
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

    <!-- Sidebar -->
    <div id="sidebar" class="bg-white shadow-lg sidebar">
        <div class="p-6 bg-green-700 text-white">
            <!-- Profile Picture and Name -->
            <div class="text-center mb-4">
                <img src="<?php echo $base_url; ?>/assets/images/profilepicture1.png" alt="Profile Picture" class="w-24 h-24 rounded-full mx-auto border-4 border-white shadow-md">
                <h2 class="mt-2 text-xl font-bold"><?php echo htmlspecialchars($user_data['username']); ?></h2>
                <p class="text-sm text-green-200">Member since <?php echo date('Y', strtotime($user_data['DateRegistered'])); ?></p>
            </div>
        </div>
        <ul class="p-6 space-y-4">
            <li><a href="<?php echo $base_url; ?>/views/user/edit_profile.php" class="block text-gray-700 hover:text-green-700">Edit Profile</a></li>
            <li><a href="<?php echo $base_url; ?>/views/issues/report_issue.php" class="block text-gray-700 hover:text-green-700">Report an Issue</a></li>
            <li><a href="<?php echo $base_url; ?>/views/issues/view_issues.php" class="block text-gray-700 hover:text-green-700">View Issues</a></li>
            <li><a href="<?php echo $base_url; ?>/views/issues/propose_solution.php" class="block text-gray-700 hover:text-green-700">Propose a Solution</a></li>
            <li><a href="#" id="createPollBtn" class="block text-gray-700 hover:text-green-700">Create Poll</a></li>
            <li><a href="<?php echo $base_url; ?>/views/poll/polls.php" class="block text-gray-700 hover:text-green-700">View Poll</a></li>
            <li>
                <a href="<?php echo $base_url; ?>/views/auth/logout.php" class="block text-gray-700 hover:text-green-700" onclick="showLoader()">Logout</a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div id="mainContent" class="main-content">
        <div class="p-4 bg-green-600 text-white flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
            </svg>
            <h1 class="text-2xl font-bold">Your Feed</h1>
        </div>
        <br>

        <!-- Tabs -->
        <div class="tabs">
            <button id="myTimelineBtn" class="active">My Time Line</button>
            <button id="globalTimelineBtn">Global Time Line</button>
        </div>

        <!-- My Timeline Content -->
        <div id="myTimeline" class="content active">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold text-green-700">Your Recent Activities</h2>
                <ul class="mt-4 space-y-4">
                    <?php
                    // Query to fetch user's recent activities
                    $myTimelineQuery = "
                (SELECT 'Poll' AS Type, Question AS Title, DateCreated FROM Poll WHERE CreatedBy = {$user_data['UserID']})
                UNION
                (SELECT 'Issue' AS Type, Title, DateSubmitted AS DateCreated FROM Issue WHERE SubmittedBy = {$user_data['UserID']})
                UNION
                (SELECT 'Petition' AS Type, Title, DateCreated FROM Petition WHERE CreatedBy = {$user_data['UserID']})
                ORDER BY DateCreated DESC";

                    $myTimelineResult = mysqli_query($conn, $myTimelineQuery);

                    // Display user's recent activities
                    while ($row = mysqli_fetch_assoc($myTimelineResult)) {
                        echo '<li class="p-4 bg-gray-50 rounded-md shadow">';
                        echo '<h3 class="text-lg font-semibold">' . $row['Type'] . ': ' . $row['Title'] . '</h3>';
                        echo '<p class="mt-2 text-gray-600">Date: ' . $row['DateCreated'] . '</p>';
                        echo '<button class="bg-blue-500 text-white py-1 px-2 rounded mt-2">Edit Post</button>';
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>

        <!-- Global Timeline Content -->
        <div id="globalTimeline" class="content">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold text-green-700">Global Recent Activities</h2>
                <ul class="mt-4 space-y-4">
                    <?php
                    // Query to fetch global recent activities
                    $globalTimelineQuery = "
                (SELECT 'Poll' AS Type, Question AS Title, DateCreated FROM Poll)
                UNION
                (SELECT 'Issue' AS Type, Title, DateSubmitted AS DateCreated FROM Issue)
                UNION
                (SELECT 'Petition' AS Type, Title, DateCreated FROM Petition)
                ORDER BY DateCreated DESC";

                    $globalTimelineResult = mysqli_query($conn, $globalTimelineQuery);

                    // Display global recent activities
                    while ($row = mysqli_fetch_assoc($globalTimelineResult)) {
                        echo '<li class="p-4 bg-gray-50 rounded-md shadow">';
                        echo '<h3 class="text-lg font-semibold">' . $row['Type'] . ': ' . $row['Title'] . '</h3>';
                        echo '<p class="mt-2 text-gray-600">Date: ' . $row['DateCreated'] . '</p>';
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>

        <!-- Create Poll Modal -->
        <div id="pollModal" class="modal">
            <div class="modal-content">
                <span class="close-btn">&times;</span>
                <h2 class="text-2xl font-bold text-green-700 mb-4">Create a New Poll</h2>
                <form action="<?php echo $base_url; ?>/views/poll/create_poll.php" method="POST">
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

        <!-- JavaScript for modal and tabs -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Tab switching functionality
                const myTimelineBtn = document.getElementById('myTimelineBtn');
                const globalTimelineBtn = document.getElementById('globalTimelineBtn');
                const myTimeline = document.getElementById('myTimeline');
                const globalTimeline = document.getElementById('globalTimeline');

                myTimelineBtn.addEventListener('click', function() {
                    myTimelineBtn.classList.add('active');
                    globalTimelineBtn.classList.remove('active');
                    myTimeline.classList.add('active');
                    globalTimeline.classList.remove('active');
                });

                globalTimelineBtn.addEventListener('click', function() {
                    globalTimelineBtn.classList.add('active');
                    myTimelineBtn.classList.remove('active');
                    globalTimeline.classList.add('active');
                    myTimeline.classList.remove('active');
                });

                // Modal functionality
                const modal = document.getElementById('pollModal');
                const closeBtn = document.querySelector('.close-btn');
                const createPollBtn = document.getElementById('createPollBtn');

                if (createPollBtn) {
                    createPollBtn.addEventListener('click', function() {
                        modal.style.display = 'block';
                    });
                }

                if (closeBtn) {
                    closeBtn.addEventListener('click', function() {
                        modal.style.display = 'none';
                    });
                }

                // Close modal when clicking outside of it
                window.addEventListener('click', function(event) {
                    if (event.target == modal) {
                        modal.style.display = 'none';
                    }
                });
            });
        </script>

</body>

</html>