<?php
include '../connection.php';
session_start();

$studentId = $_GET["studentId"];
$holdDate = $_GET["holdDate"];
$holdType = $_GET["holdType"];

$sql = "DELETE FROM student_holds WHERE Student_ID='".$studentId."' AND Hold_Date='".$holdDate."' AND Hold_Type='".$holdType."'";
if ($conn->query($sql) === TRUE) {
    header('location:adminRemoveStudentHold.php');
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

?>