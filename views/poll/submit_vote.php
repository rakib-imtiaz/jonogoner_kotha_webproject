<?php
include '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pollID = $_POST['pollID'];
    $optionID = $_POST['optionID'];

    // Update vote count
    $query = "UPDATE PollOption SET VoteCount = VoteCount + 1 WHERE OptionID = $optionID";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
