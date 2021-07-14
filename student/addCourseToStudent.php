<?php
session_start();
include '../connection.php';

    $userId = $_SESSION["user_id"]];
    $courseID = $_GET["courseID"];


    $term = $_GET["term"];
    echo $term;
    $studentId = $_SESSION["user_id"]];

    $sqlPre = "SELECT * FROM course WHERE Course_ID = '".$courseID."'";
    $queryPre = mysqli_query($conn, $sqlPre);
    $rowPre = mysqli_fetch_assoc($queryPre);

    $sqlEnrollment = "SELECT * FROM enrollment WHERE Student_ID = '".$studentId."'";
    $sql = "INSERT INTO enrollment (Student_ID, CRN_Num, Date_Enrolled) VALUES (".$userId.",'".$courseID."', CURDATE())";

    if($term == 'Spring 2021'){
      if ($conn->query($sql) === TRUE) {
        $_SESSION["credits"] = $_SESSION["credits"] + $_GET["credits"];
        $_SESSION["required_creds"] = $_SESSION["required_creds"] - $_GET["credits"];

          header('location:addDropClass.php');
      } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }
    }else{
      if ($conn->query($sql) === TRUE) {
        echo $_SESSION["credits2"];
        echo '\n';
        echo $_SESSION["credits"];
        $_SESSION["credits2"] = $_SESSION["credits2"] + $_GET["credits"];
        echo $_SESSION["credits2"];
        echo '\n';
        echo $_SESSION["credits"];
        $_SESSION["required_creds2"] = $_SESSION["required_creds2"] - $_GET["credits"];

          header('location:addDropClass.php');
      } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }
    }



?>
