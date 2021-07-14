<?php
include '../connection.php';
session_start();

$holdId = $_GET["holdId"];
$sql = "DELETE FROM hold WHERE Hold_ID=".$holdId."";
if ($conn->query($sql) === TRUE) {
    header('location:removeHold.php');
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

?>