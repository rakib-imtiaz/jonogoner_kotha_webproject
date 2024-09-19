<?php
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Fetch all issues for dropdown selection
$issue_query = "SELECT IssueID, Title FROM Issue";
$issues_result = mysqli_query($conn, $issue_query);

// Process the form for proposing a solution
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $issue_id = mysqli_real_escape_string($conn, $_POST['issue']);
    $solution_title = mysqli_real_escape_string($conn, $_POST['title']);
    $solution_description = mysqli_real_escape_string($conn, $_POST['description']);
    $submitted_by = $_SESSION['user'];  // Get username from session

    $insert_query = "INSERT INTO Solution (IssueID, Title, Description, DateSubmitted, SubmittedBy) 
                     VALUES ('$issue_id', '$solution_title', '$solution_description', NOW(), 
                     (SELECT UserID FROM User WHERE Username = '$submitted_by'))";

    if (mysqli_query($conn, $insert_query)) {
        echo "Solution proposed successfully!";
    } else {
        echo "Error proposing solution.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Propose a Solution - Jonogoner Kotha</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">

    <!-- Navbar -->
    <?php include 'templates/navbar.php'; ?>

    <!-- Propose a Solution Form -->
    <div class="container mx-auto py-16">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md mx-auto">
            <h1 class="text-3xl font-bold text-green-700 mb-6">Propose a Solution</h1>

            <form action="propose_solution.php" method="POST">
                <div class="mb-4">
                    <label for="issue" class="block text-gray-700">Select Issue</label>
                    <select id="issue" name="issue" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
                        <?php while ($issue = mysqli_fetch_assoc($issues_result)): ?>
                            <option value="<?php echo $issue['IssueID']; ?>"><?php echo $issue['Title']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="title" class="block text-gray-700">Solution Title</label>
                    <input type="text" id="title" name="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700">Description</label>
                    <textarea id="description" name="description" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required></textarea>
                </div>
                <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-500">Submit Solution</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'templates/footer.php'; ?>

</body>
</html>
