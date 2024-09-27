<?php
// Include the configuration file
include '../../includes/config.php';

// Check if a poll ID is provided in the GET request
if (isset($_GET['pollID'])) {
    // Sanitize the input to prevent SQL injection
    $pollID = mysqli_real_escape_string($conn, $_GET['pollID']);

    // Prepare the SQL query to fetch poll results
    $query = "SELECT PollOption.OptionID, PollOption.OptionText, COUNT(Vote.VoteID) as Votes
              FROM PollOption
              LEFT JOIN Vote ON PollOption.OptionID = Vote.OptionID
              WHERE PollOption.PollID = $pollID
              GROUP BY PollOption.OptionID";

    // Execute the query
    $result = mysqli_query($conn, $query);

    $pollResults = [];
    $totalVotes = 0;

    // Process the query results
    while ($row = mysqli_fetch_assoc($result)) {
        $pollResults[$row['OptionID']] = [
            'optionText' => $row['OptionText'],
            'votes' => $row['Votes']
        ];
        $totalVotes += $row['Votes'];
    }

    // Return the poll results and total votes as JSON
    echo json_encode(['results' => $pollResults, 'totalVotes' => $totalVotes]);
} else {
    // Return an error message if no poll ID is provided
    echo json_encode(['error' => 'Invalid request']);
}
?>
