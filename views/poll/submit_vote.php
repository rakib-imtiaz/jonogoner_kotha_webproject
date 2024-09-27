<?php
session_start();
include '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $pollID = mysqli_real_escape_string($conn, $_POST['pollID']);
    $optionID = mysqli_real_escape_string($conn, $_POST['optionID']);
    $userID = $_SESSION['user_id'];

    // Check if the user has already voted on this poll
    $check_vote = "SELECT * FROM Vote WHERE UserID = $userID AND PollID = $pollID";
    $result = mysqli_query($conn, $check_vote);

    if (mysqli_num_rows($result) == 0) {
        // User hasn't voted yet, insert new vote
        $insert_vote = "INSERT INTO Vote (UserID, PollID, OptionID) VALUES ($userID, $pollID, $optionID)";
        if (mysqli_query($conn, $insert_vote)) {
            echo json_encode(['success' => true, 'message' => 'Vote submitted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error submitting vote']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'You have already voted on this poll']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request or user not logged in']);
}
?>
