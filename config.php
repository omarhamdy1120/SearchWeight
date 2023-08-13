<?php
$host = "localhost";
$username = "adamdesign_dbu";
$password = "MTt5E4FU8FGiyW9";
$database = "adamdesign_simple";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>