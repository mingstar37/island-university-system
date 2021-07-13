<?php
include '../connection.php';
session_start();

$fullPart = "Full Time";
$advisors = array();
$faculties = array();
$coursesTrans = array();
$crnsTrans = array();
$gradeYear = array();
$coursesEnrolled = array();
$crnsEnrolled = array();
$gradeYear2 = array();


$student_fetched = false;
if(isset($_POST["submit"])){
    $sId = $_POST["studentId"];
    $advisorId = $_POST["advisorId"];
    $timeAdvisement = $_POST["timeAdvisement"];

    $sqlAdd = "INSERT INTO advisor (`Faculty_ID`, `Student_ID`, `Time_Of_Advisement`) VALUES ('".$advisorId."', '".$sId."', '".$timeAdvisement."')";
    if ($conn->query($sqlAdd) === TRUE) {
        echo "<script>alert('Advisor added successfully')</script>";
      } else {
        echo "<script>alert('Error: " . $sqlAdd . "<br>" . $conn->error."')</script>";
      }

    // advisor
    $sqlAdvisor = "SELECT * FROM advisor WHERE Student_ID = '".$sId."'";
    $queryAdvisor = mysqli_query($conn,$sqlAdvisor);

    $student_fetched = true;
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


            <h3 id="add-student-section"> Add Student Advisors</h3>
            <br>
           <form method="post" action="">
               Enter Advisor's Faculty ID: <input type="text" name="advisorId" placeholder="Faculty Id" required/> <br><br>
               Enter Student ID: <input type="text" name="studentId" placeholder="Student Id" required/> <br><br>
                Enter Time of Advisement: <input type="text" name="timeAdvisement" placeholder="Time of Advisement" required/> <br><br>
                <input type="submit" class="btn btn-primary" name="submit" value="Add Student Advisor" />
        <form>

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
