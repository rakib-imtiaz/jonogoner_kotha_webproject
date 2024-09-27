<?php
// Start the session and include the configuration file
session_start();
include '../../includes/config.php';

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);

// Fetch polls from the database
$query = "SELECT Poll.PollID, Poll.Question, Poll.DateCreated, Poll.IsActive, PollOption.OptionID, PollOption.OptionText, 
          (SELECT COUNT(*) FROM Vote WHERE Vote.OptionID = PollOption.OptionID) as Votes,
          (SELECT COUNT(*) FROM Vote WHERE Vote.PollID = Poll.PollID) as TotalVotes
          FROM Poll 
          JOIN PollOption ON Poll.PollID = PollOption.PollID
          WHERE Poll.IsActive = 1";
$result = mysqli_query($conn, $query);

// Poll options data structured by poll ID
$polls = [];
while ($row = mysqli_fetch_assoc($result)) {
    $polls[$row['PollID']]['Question'] = $row['Question'];
    $polls[$row['PollID']]['DateCreated'] = $row['DateCreated'];
    $polls[$row['PollID']]['TotalVotes'] = $row['TotalVotes'];
    $polls[$row['PollID']]['Options'][] = [
        'OptionID' => $row['OptionID'],
        'OptionText' => $row['OptionText'],
        'Votes' => $row['Votes']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Polls - Jonogoner Kotha</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .poll-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .poll-option {
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .poll-option:hover {
            background-color: #f0f0f0;
        }

        .message {
            font-size: 1rem;
            color: red;
            margin-top: 10px;
        }

        .percentage {
            font-weight: bold;
            color: gray;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<style>
    body {
        background-image: url('/assets/images/emergency_post.png');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        background-repeat: no-repeat;
        background-color: #f4f4f4;
    }
</style>

<body class="bg-gray-100 text-gray-800">

    <!-- Navbar -->
    <?php include '../templates/header.php'; ?>

    <!-- Polls Section -->
    <div class="container mx-auto py-16">
        <h1 class="text-3xl font-bold text-green-700 mb-6">Live Polls</h1>

        <?php foreach ($polls as $pollID => $poll): ?>
            <div class="poll-container mb-6">
                <h2 class="text-2xl font-bold mb-4"><?php echo $poll['Question']; ?></h2>
                <p class="text-sm text-gray-600 mb-4">Created on: <?php echo date('Y-m-d', strtotime($poll['DateCreated'])); ?></p>

                <form class="poll-form" data-poll-id="<?php echo $pollID; ?>">
                    <?php foreach ($poll['Options'] as $option):
                        $percentage = ($poll['TotalVotes'] > 0) ? round(($option['Votes'] / $poll['TotalVotes']) * 100) : 0;
                    ?>
                        <div class="poll-option p-2 border mb-2 rounded-lg" data-option-id="<?php echo $option['OptionID']; ?>">
                            <label>
                                <input type="radio" name="poll_option_<?php echo $pollID; ?>" value="<?php echo $option['OptionID']; ?>" <?php if (!$isLoggedIn) echo 'disabled'; ?>>
                                <?php echo $option['OptionText']; ?>
                            </label>
                            <span class="percentage"><?php echo $percentage; ?>%</span>
                        </div>
                    <?php endforeach; ?>

                    <?php if (!$isLoggedIn): ?>
                        <p class="message">Log in to participate in the nation's voice!</p>
                    <?php else: ?>
                        <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-500">Submit Vote</button>
                    <?php endif; ?>
                </form>

                <!-- Display poll result here -->
                <div class="poll-result mt-4 hidden">
                    <h3 class="text-xl font-bold">Live Results:</h3>
                    <div class="results"></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Footer -->
    <?php include '../templates/footer.php'; ?>

    <script>
        $(document).ready(function() {
            // Handle the vote submission
            $('.poll-form').on('submit', function(event) {
                event.preventDefault();

                var pollID = $(this).data('poll-id');
                var selectedOption = $(this).find('input[name="poll_option_' + pollID + '"]:checked').val();

                if (selectedOption) {
                    $.ajax({
                        url: 'submit_vote.php',
                        method: 'POST',
                        data: {
                            pollID: pollID,
                            optionID: selectedOption
                        },
                        success: function(response) {
                            var result = JSON.parse(response);
                            if (result.success) {
                                alert(result.message);
                                fetchPollResults(pollID);
                                // Disable the form to prevent multiple votes
                                $('form[data-poll-id="' + pollID + '"]').find('input[type="radio"]').prop('disabled', true);
                                $('form[data-poll-id="' + pollID + '"]').find('button[type="submit"]').prop('disabled', true);
                            } else {
                                alert(result.message);
                            }
                        }
                    });
                } else {
                    alert('Please select an option.');
                }
            });

            // Function to fetch and display poll results
            function fetchPollResults(pollID) {
                $.ajax({
                    url: 'fetch_poll_results.php',
                    method: 'GET',
                    data: {
                        pollID: pollID
                    },
                    success: function(response) {
                        var pollResultDiv = $('form[data-poll-id="' + pollID + '"]').siblings('.poll-result');
                        var resultHTML = '';

                        $.each(response.results, function(optionID, data) {
                            var percentage = (data.votes / response.totalVotes) * 100;
                            resultHTML += '<p>' + data.optionText + ': ' + percentage.toFixed(2) + '%</p>';
                        });

                        pollResultDiv.find('.results').html(resultHTML);
                        pollResultDiv.removeClass('hidden');
                    }
                });
            }
        });
    </script>


</body>

</html>