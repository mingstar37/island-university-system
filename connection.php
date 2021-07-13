<?php
$servername = "localhost";
$username = "u611050769_root";
$password = "Root@321";
$database = "u611050769_info";
// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
