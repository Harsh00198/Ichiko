<?php
$host = "localhost";
$users = "root";
$pass = "";
$db = "vintage store";

$conn = new mysqli($host, $users, $pass, $db);

if ($conn->connect_error) {
    echo "Failed to connect to DB: " . $conn->connect_error;
} else {
    echo "Connected successfully";
}
?>
