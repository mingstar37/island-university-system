<?php
include '../connection.php';
session_start();


$studentId =$_GET["studentId"];
$major_id = $_GET["majorId"];
$type = $_GET["type"];

$sqlDel = "DELETE FROM student_major WHERE Student_ID = '".$studentId."'";
$sql = "INSERT INTO student_major (`Student_ID`, `Major_ID`, `type`) VALUES ('".$studentId."', '".$major_id."', '" . $type . "')";

if ($conn->query($sqlDel) === TRUE) {
    if ($conn->query($sql) === TRUE) {
        echo 'Successfully added major';
        header('location:adminAddStudentMajor.php');
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
  echo "Error: " . $sqlDel . "<br>" . $conn->error;
}

?>