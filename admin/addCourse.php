<?php
include '../connection.php';
session_start();

$course_added = false;

if(isset($_POST["submit"])){
    // $dn = $_POST['dn'];
    $df = $_POST['df'];
    $dl = $_POST['dl'];
    $dbn = $_POST['dbn'];

    // $crn = $_POST['crn'];
    // $fid = $_POST['fid'];
    // $rnum = $_POST['rnum'];
    // $bnum = $_POST['bnum'];
    // $aseat = $_POST['aseat'];
    // $sem = $_POST['sem'];
    // $sec = $_POST['sec'];
    // $tslot = '250011';

    $sql2 = "INSERT INTO course (`Dept_ID`, `Course_Name`, `Course_Credits`) VALUES ('$df', '$dl', '$dbn')";

    // $sqlSection = "INSERT INTO section VALUES ('".$crn."', '".$dId."', '".$fid."', '".$rnum."', '".$bnum."', '".$aseat."', '".$sem."', '".$tslot."', '".$sec."')";
    if ($conn->query($sql2) === TRUE) {
        // echo 'Successfully added course';
        // if ($conn->query($sqlSection) === TRUE) {
        // echo 'Successfully added course';
        echo '<script>alert("Added Course Successfully")</script>';
        header("Location:courses.php");
        // } else {
        //   echo "Error: " . $sqlSection . "<br>" . $conn->error;
        // }
    } else {
      echo "Error: " . $sql2 . "<br>" . $conn->error;
    }

    $course_added = true;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Island</title>

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


            <h3 id="add-student-section"> Add Course </h3>
            <br>
            <?php
            if ($course_added) {
                echo "Department Added Successfully";
            }
            ?>
           <form method="post" action="">
<!--                Course Id: <input type="text" name="dId" placeholder="Course Id" /> <br><br>-->
                <!-- Pre requisite Course ID: <input type="text" name="dn" placeholder="Pre requisite Course ID" /> <br><br> -->
                Department ID: <input type="text" name="df" placeholder="Department ID" /> <br><br>
                Course Name: <input type="text" name="dl" placeholder="Course Name" /> <br><br>
                Course Credits: <input type="text" name="dbn" placeholder="Course Credits" /> <br><br>

                <!-- CRN Number: <input type="text" name="crn" placeholder="CRN Number" /> <br><br>
                Faculty ID: <input type="text" name="fid" placeholder="Faculty ID" /> <br><br>
                Room Number: <input type="text" name="rnum" placeholder="Room Number" /> <br><br>
                Building Name: <input type="text" name="bnum" placeholder="Building Name" /> <br><br>
                Available Seat: <input type="text" name="aseat" placeholder="Available Seat" /> <br><br>
                Semester:
                <select name="sem">
                    <option value="Spring 2021">Spring 2021</option>
                    <option value="Fall 2021">Fall 2021</option>
                </select>
                <br><br>
                Section: <input type="text" name="sec" placeholder="Section" /> <br><br> -->

                <input type="submit" class="btn btn-primary" name="submit" value="Add Course" />
        <form>

        <br><br>
        </div>
    </div>


    </div>
    </div>

    <script src="../plugins/js/nav.js"></script>

</body>

</html>
