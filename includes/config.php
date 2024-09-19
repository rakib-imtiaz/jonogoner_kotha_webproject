<?php

// $servername = "127.0.0.1";  // Your server name
// $username = "root";         // Your database username
// $password = "root";         // Your database password
// $dbname = "jonogoner_kotha"; // Your database name

$servername = "sql100.infinityfree.com";  // Your server name
$username = "if0_37337710";         // Your database username
$password = "s2hFTkVbNbfKLm";         // Your database password
$dbname = "if0_37337710_jonogoner_kotha"; // Your database name


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("<script>alert('Connection failed: " . $conn->connect_error . "');</script>");
} else {
    // echo "<script>alert('Connected to database successfully');</script>";
}

?>
