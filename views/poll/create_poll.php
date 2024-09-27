<?php
session_start();
include '../../includes/config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: " . $base_url . "/views/auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Escape and store form inputs
    $question = mysqli_real_escape_string($conn, $_POST['poll_question']);
    $option1 = mysqli_real_escape_string($conn, $_POST['poll_option_1']);
    $option2 = mysqli_real_escape_string($conn, $_POST['poll_option_2']);
    $option3 = mysqli_real_escape_string($conn, $_POST['poll_option_3']);
    $user_id = $_SESSION['user_id'];

    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Insert the poll into the database
        $insert_poll = "INSERT INTO Poll (Question, DateCreated, CreatedBy, IsActive) VALUES (?, NOW(), ?, TRUE)";
        $stmt = mysqli_prepare($conn, $insert_poll);
        mysqli_stmt_bind_param($stmt, "si", $question, $user_id);
        mysqli_stmt_execute($stmt);
        $poll_id = mysqli_insert_id($conn);

        // Insert poll options
        $insert_option = "INSERT INTO PollOption (PollID, OptionText) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $insert_option);

        // Insert first two options (required)
        mysqli_stmt_bind_param($stmt, "is", $poll_id, $option1);
        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_param($stmt, "is", $poll_id, $option2);
        mysqli_stmt_execute($stmt);

        // Insert third option if provided
        if (!empty($option3)) {
            mysqli_stmt_bind_param($stmt, "is", $poll_id, $option3);
            mysqli_stmt_execute($stmt);
        }

        // Commit transaction
        mysqli_commit($conn);

        // Redirect to the polls page with a success message
        header("Location: " . $base_url . "/views/poll/polls.php?success=1");
        exit();
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        $error_message = "An error occurred while creating the poll. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Poll - Jonogoner Kotha</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Create a New Poll</h1>
        
        <?php if (isset($error_message)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo $error_message; ?></span>
            </div>
        <?php endif; ?>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="poll_question">
                    Poll Question
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="poll_question" name="poll_question" type="text" placeholder="Enter your poll question" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="poll_option_1">
                    Option 1
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="poll_option_1" name="poll_option_1" type="text" placeholder="Enter option 1" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="poll_option_2">
                    Option 2
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="poll_option_2" name="poll_option_2" type="text" placeholder="Enter option 2" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="poll_option_3">
                    Option 3 (Optional)
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="poll_option_3" name="poll_option_3" type="text" placeholder="Enter option 3 (optional)">
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Create Poll
                </button>
            </div>
        </form>
    </div>
</body>
</html>
