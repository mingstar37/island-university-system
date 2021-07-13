<?php
session_start();
include '../connection.php';

$toDelete = $_GET["userId"];

$sql = "DELETE FROM researcher WHERE Researcher_ID = '".$toDelete."'";
$sql2 = "DELETE FROM users WHERE User_ID = '".$toDelete."'";
$sql3 = "DELETE FROM login_info WHERE User_ID = '".$toDelete."'";

if ($conn->query($sql) === TRUE) {

    if ($conn->query($sql2) === TRUE) {

        if ($conn->query($sql3) === TRUE) {
          } else {
            echo "Error: " . $sql3 . "<br>" . $conn->error;
          }
      } else {
        echo "Error: " . $sql2 . "<br>" . $conn->error;
      }
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  header('location:researcher.php');

?>