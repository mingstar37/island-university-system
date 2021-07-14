<?php
session_start();
include '../connection.php';

    $userId = $_GET["studentID"];
    $courseID = $_GET["courseID"];


    $sql = "INSERT INTO enrollment (Student_ID, CRN_Num, Date_Enrolled) VALUES (".$userId.",'".$courseID."',NOW())";

    if ($conn->query($sql) === TRUE) {
      $_SESSION["credits"] = $_SESSION["credits"] + $_GET["credits"];
      $_SESSION["required_creds"] = $_SESSION["required_creds"] - $_GET["credits"];

        header('location:adminAddStudentCourse.php');
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }


?>