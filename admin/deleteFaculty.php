<?php
session_start();
include '../connection.php';

$toDelete = $_GET["fId"];

$sql  = "DELETE FROM faculty WHERE Faculty_ID = '".$toDelete."'";
$sql2 = "DELETE FROM users WHERE User_ID = '".$toDelete."'";
$sql3 = "DELETE FROM login_info WHERE User_ID = '".$toDelete."'";
$sql4 = "DELETE FROM full_t_faculty WHERE Faculty_ID = '".$toDelete."'";
$sql5 = "DELETE FROM part_t_faculty WHERE Faculty_ID = '".$toDelete."'";

if ($conn->query($sql) === TRUE) {

    if ($conn->query($sql2) === TRUE) {

        if ($conn->query($sql3) === TRUE) {
            if ($conn->query($sql3) === TRUE) {
            } else {
              echo "Error: " . $sql3 . "<br>" . $conn->error;
            }
            if ($conn->query($sql4) === TRUE) {
               
            } else {
              echo "Error: " . $sql4 . "<br>" . $conn->error;
            }
            if ($conn->query($sql5) === TRUE) {
               
            } else {
              echo "Error: " . $sql5 . "<br>" . $conn->error;
            }
          } else {
            echo "Error: " . $sql3 . "<br>" . $conn->error;
          }
      } else {
        echo "Error: " . $sql2 . "<br>" . $conn->error;
      }
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  header('location:faculty.php');

?>