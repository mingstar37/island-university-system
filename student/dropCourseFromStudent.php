<?php
    session_start();
    include '../connection.php';

    $userId = $_SESSION["user_id"]];
    $courseID = $_GET["courseID"];

    echo $userId;
    echo $courseID;
    $sql = "DELETE FROM enrollment WHERE Student_ID='".$userId."' and CRN_Num='".$courseID."'";
    $query = mysqli_query($conn,$sql);



    if ($conn->query($sql) === TRUE) {
      // $_SESSION["credits"] = $_SESSION["credits"] - $_GET["credits"];
      // $_SESSION["required_creds"] = $_SESSION["required_creds"] + $_GET["credits"];

        header('location:addDropClass.php');
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }

?>
