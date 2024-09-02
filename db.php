<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "luster_software";

// $servername = "longwellconnect.com";
// $username = "u500921674_luster";
// $password = "OnGod@123";
// $dbname = "u500921674_luster";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>