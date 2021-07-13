<?php
session_start();
include '../connection.php';

$toDelete = $_GET["courseId"];
$type = $_GET['type'];

if ($type === 'all') {
    $sql = "DELETE FROM course WHERE Course_ID = '".$toDelete."'";
} else {
    $sql = "UPDATE course SET `Prereq_Course_ID` = '0' WHERE Course_ID = '".$toDelete."'";
}


if ($conn->query($sql) === TRUE) {
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  echo '<script>alert("Course Deleted Successfully")</script>';
  header('location:courses.php');

?>