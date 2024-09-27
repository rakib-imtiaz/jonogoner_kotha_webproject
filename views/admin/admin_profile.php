<?php
// Start the session and include the configuration file
session_start();
include '../../includes/config.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    // Redirect to login page if not an admin
    header("Location: " . $base_url . "/views/auth/login.php");
    exit();
}

// Fetch admin information from the database
$admin_id = $_SESSION['user_id'];
$admin_query = "SELECT * FROM User WHERE UserID = $admin_id";
$admin_result = mysqli_query($conn, $admin_query);
$admin_data = mysqli_fetch_assoc($admin_result);

// Fetch all users with 'User' role
$users_query = "SELECT * FROM User WHERE Role = 'User'";
$users_result = mysqli_query($conn, $users_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - Jonogoner Kotha</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
    <style>
        /* CSS animations and styles */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        .gradient-bg {
            background: linear-gradient(120deg, #84fab0 0%, #8fd3f4 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
    </style>
</head>
<body class="bg-gray-100 gradient-bg min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold mb-8 text-white text-center fade-in">Admin Dashboard</h1>
        
        <!-- Admin Information Section -->
        <div class="glass-effect p-6 mb-8 fade-in">
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">Admin Information</h2>
            <p class="text-gray-700"><strong>Username:</strong> <?php echo $admin_data['username']; ?></p>
            <p class="text-gray-700"><strong>Email:</strong> <?php echo $admin_data['Email']; ?></p>
            <p class="text-gray-700"><strong>Role:</strong> <?php echo $admin_data['Role']; ?></p>
        </div>

        <!-- User Management Section -->
        <div class="glass-effect p-6 fade-in" x-data="{ activeTab: 'view' }">
            <!-- Tab Navigation -->
            <div class="mb-4 flex justify-center">
                <button @click="activeTab = 'view'" :class="{ 'bg-blue-500 text-white': activeTab === 'view', 'bg-gray-200 text-gray-700': activeTab !== 'view' }" class="px-4 py-2 rounded-lg mr-2 transition duration-300 ease-in-out transform hover:scale-105">View Users</button>
                <button @click="activeTab = 'edit'" :class="{ 'bg-blue-500 text-white': activeTab === 'edit', 'bg-gray-200 text-gray-700': activeTab !== 'edit' }" class="px-4 py-2 rounded-lg mr-2 transition duration-300 ease-in-out transform hover:scale-105">Edit Users</button>
                <button @click="activeTab = 'delete'" :class="{ 'bg-blue-500 text-white': activeTab === 'delete', 'bg-gray-200 text-gray-700': activeTab !== 'delete' }" class="px-4 py-2 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">Delete Users</button>
            </div>

            <!-- View Users Tab -->
            <div x-show="activeTab === 'view'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100">
                <h3 class="text-xl font-semibold mb-4 text-gray-800">View Users</h3>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="px-4 py-2 text-left">Username</th>
                                <th class="px-4 py-2 text-left">Email</th>
                                <th class="px-4 py-2 text-left">Date Registered</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($user = mysqli_fetch_assoc($users_result)) : ?>
                                <tr class="hover:bg-gray-100">
                                    <td class="border px-4 py-2"><?php echo $user['username']; ?></td>
                                    <td class="border px-4 py-2"><?php echo $user['Email']; ?></td>
                                    <td class="border px-4 py-2"><?php echo $user['DateRegistered']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Edit Users Tab -->
            <div x-show="activeTab === 'edit'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100">
                <h3 class="text-xl font-semibold mb-4 text-gray-800">Edit Users</h3>
                <form action="edit_user.php" method="POST" class="space-y-4">
                    <div>
                        <label for="user_id" class="block mb-1 text-gray-700">Select User:</label>
                        <select name="user_id" id="user_id" class="w-full p-2 border rounded">
                            <?php 
                            mysqli_data_seek($users_result, 0);
                            while ($user = mysqli_fetch_assoc($users_result)) : 
                            ?>
                                <option value="<?php echo $user['UserID']; ?>"><?php echo $user['username']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div>
                        <label for="new_email" class="block mb-1 text-gray-700">New Email:</label>
                        <input type="email" name="new_email" id="new_email" class="w-full p-2 border rounded">
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded transition duration-300 ease-in-out transform hover:scale-105">Update User</button>
                </form>
            </div>

            <!-- Delete Users Tab -->
            <div x-show="activeTab === 'delete'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100">
                <h3 class="text-xl font-semibold mb-4 text-gray-800">Delete Users</h3>
                <form action="delete_user.php" method="POST" class="space-y-4">
                    <div>
                        <label for="user_id_delete" class="block mb-1 text-gray-700">Select User to Delete:</label>
                        <select name="user_id_delete" id="user_id_delete" class="w-full p-2 border rounded">
                            <?php 
                            mysqli_data_seek($users_result, 0);
                            while ($user = mysqli_fetch_assoc($users_result)) : 
                            ?>
                                <option value="<?php echo $user['UserID']; ?>"><?php echo $user['username']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded transition duration-300 ease-in-out transform hover:scale-105" onclick="return confirm('Are you sure you want to delete this user?')">Delete User</button>
                </form>
            </div>
        </div>

        <!-- Logout Button -->
        <div class="mt-8 text-center">
            <a href="<?php echo $base_url; ?>/views/auth/logout.php" class="bg-red-500 text-white px-6 py-3 rounded-lg transition duration-300 ease-in-out transform hover:scale-105 inline-block">Logout</a>
        </div>
    </div>
    <script>
        // Add fade-in effect to elements
        document.addEventListener('DOMContentLoaded', (event) => {
            document.querySelectorAll('.fade-in').forEach((element, index) => {
                setTimeout(() => {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, index * 200);
            });
        });
    </script>
</body>
</html>
