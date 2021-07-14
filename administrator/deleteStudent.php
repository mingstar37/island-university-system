<?php
session_start();
include '../connection.php';

$toDelete = $_GET["userId"];

$sql  = "DELETE FROM student WHERE Student_ID = '".$toDelete."'";
$sql2 = "DELETE FROM users WHERE User_ID = '".$toDelete."'";
$sql3 = "DELETE FROM login_info WHERE User_ID = '".$toDelete."'";
$sql4 = "DELETE FROM undergraduate WHERE Student_ID = '".$toDelete."'";
$sql5 = "DELETE FROM graduate WHERE Student_ID = '".$toDelete."'";
$sql6 = "DELETE FROM parttime_undergrad WHERE Student_ID = '".$toDelete."'";
$sql7 = "DELETE FROM parttime_grad WHERE Student_ID = '".$toDelete."'";
$sql8 = "DELETE FROM full_time_grad WHERE Student_ID = '".$toDelete."'";
$sql9 = "DELETE FROM full_time_undergrad WHERE Student_ID = '".$toDelete."'";

if ($conn->query($sql) === TRUE) {

    if ($conn->query($sql2) === TRUE) {

        if ($conn->query($sql3) === TRUE) {
            if ($conn->query($sql3) === TRUE) {
            } else {
              echo "Error: " . $sql3 . "<br>" . $conn->error;
            }
            if ($conn->query($sql4) === TRUE) {
                if ($conn->query($sql6) === TRUE) {
                } else {
                  echo "Error: " . $sql6 . "<br>" . $conn->error;
                }
                if ($conn->query($sql9) === TRUE) {
                } else {
                  echo "Error: " . $sql9 . "<br>" . $conn->error;
                }
            } else {
              echo "Error: " . $sql4 . "<br>" . $conn->error;
            }
            if ($conn->query($sql5) === TRUE) {
                if ($conn->query($sql7) === TRUE) {
                } else {
                  echo "Error: " . $sql7 . "<br>" . $conn->error;
                }
                if ($conn->query($sql8) === TRUE) {
                } else {
                  echo "Error: " . $sql8 . "<br>" . $conn->error;
                }
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

  header('location:addStudent.php');

?>