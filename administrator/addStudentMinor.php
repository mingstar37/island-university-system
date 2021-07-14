<?php
include '../connection.php';
session_start();


$studentId =$_GET["studentId"];
$major_id = $_GET["minorId"];
$type = $_GET["type"];

$sqlDel = "DELETE FROM student_minor WHERE Student_ID = '".$studentId."'";
$sql = "INSERT INTO student_minor (`Student_ID`, `Minor_ID`, `type`) VALUES  ('".$studentId."', '".$major_id."', '" . $type . "')";

if ($conn->query($sqlDel) === TRUE) {
    if ($conn->query($sql) === TRUE) {
        echo 'Successfully added minor';
        header('location:adminAddStudentMinor.php');
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
  echo "Error: " . $sqlDel . "<br>" . $conn->error;
}

?>