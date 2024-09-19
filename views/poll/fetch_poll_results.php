<?php
include '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $pollID = $_GET['pollID'];

    // Fetch total votes
    $queryTotalVotes = "SELECT SUM(VoteCount) as totalVotes FROM PollOption WHERE PollID = $pollID";
    $resultTotalVotes = mysqli_query($conn, $queryTotalVotes);
    $totalVotes = mysqli_fetch_assoc($resultTotalVotes)['totalVotes'];

    // Fetch poll options and their vote counts
    $queryOptions = "SELECT OptionID, VoteCount FROM PollOption WHERE PollID = $pollID";
    $resultOptions = mysqli_query($conn, $queryOptions);

    $pollResults = [];
    while ($row = mysqli_fetch_assoc($resultOptions)) {
        $percentage = ($totalVotes > 0) ? round(($row['VoteCount'] / $totalVotes) * 100) : 0;
        $pollResults[$row['OptionID']] = $percentage;
    }

    echo json_encode(['results' => $pollResults]);
}
?>
