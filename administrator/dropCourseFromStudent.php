<?php
    session_start();
    include '../connection.php';

    $userId = $_GET["studentID"];
    $courseID = $_GET["courseID"];

    echo $userId;
    echo $courseID;
    $sql = "DELETE FROM enrollment WHERE Student_ID='".$userId."' and CRN_Num='".$courseID."'";
    $query = mysqli_query($conn,$sql);



    if ($conn->query($sql) === TRUE) {
      
        header('location:adminAddStudentCourse.php');
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }

?>