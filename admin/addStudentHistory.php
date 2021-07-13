<?php
include '../connection.php';
session_start();


$student_fetched = false;
if(isset($_POST["submit"])){
    $studentId = $_POST["studentId"];
    $crnNum = $_POST["crnNum"];
    $grade = $_POST["grade"];


    $sqlAdd = "INSERT INTO `student_history` (`Student_ID`, `CRN_Num`, `Grade`, `year`) VALUES ('" . $studentId . "', '" . $crnNum. "', '" . $grade. "', '" . 2021 . "')";
    if ($conn->query($sqlAdd) === TRUE) {

        echo "<script>alert('Student history added successfully')</script>";

    } else {
        echo "<script>alert('Error: " . $sqlAdd . "<br>" . $conn->error."')</script>";
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Island - Add Department</title>

    <link rel="stylesheet" href="../plugins/css/index.css">
    <link rel="stylesheet" href="../plugins/css/navbar.css">
    <link rel="stylesheet" href="../plugins/css/admin.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>

<body>
 <?php
    include "header.php";
    ?>


<div id="main-section">


    <?php
    include 'sidenav.php';
    ?>
    <div class="main-page">


        <h3 id="add-student-section"> Add Student History</h3>
        <br>
        <form method="post" action="">
            Enter Student ID: <input type="text" name="studentId" placeholder="Student Id" required/> <br><br>
            Enter CRN Number: <input type="text" name="crnNum" placeholder="CRN Number" required/> <br><br>
            Enter Grade: <input type="text" name="grade" placeholder="Grade" required/> <br><br>
            <input type="submit" class="btn btn-primary" name="submit" value="Add Student History" />
            </form>

                <br>
                <hr>
                <br>

    </div>
</div>


</div>
</div>



<script src="../plugins/js/nav.js"></script>

</body>

</html>
