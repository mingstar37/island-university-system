<?php
include '../connection.php';
session_start();

$course_added = false;

if(isset($_POST["edit"])){
    $dId = $_POST['dId'];
    $dn = $_POST['dn'];
    $df = $_POST['df'];
    $dl = $_POST['dl'];
    $dbn = $_POST['dbn'];
    $sql3 = "UPDATE course SET Prereq_Course_ID = '".$dn."', Dept_ID = '".$df."', Course_Name = '".$dl."', Course_Credits = '".$dbn."' WHERE Course_ID = '".$dId."'";

    if ($conn->query($sql3) === TRUE) {
        // echo 'Successfully updated course';
        echo '<script>alert("Course Updated Successfully")</script>';
        // header('location:editCourse.php');
    } else {
      echo "Error: " . $sql3 . "<br>" . $conn->error;
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
    <title>Island - Admin/ Course</title>

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
        <!-- <h1 id="banner"> Island University - Admin </h1> -->

        <?php
        include "sidenav.php";
        ?>
        <div class="main-page">


            <h3 id="add-student-section"> Edit Course </h3>
            <br>
            <?php
            if ($course_added) {
                echo "Department Added Successfully";
            }
            ?>
           <form method="post" action="">
                Course Id: <input type="text" name="dId" placeholder="Course Id" /> <br><br>
                Pre requisite Course ID: <input type="text" name="dn" placeholder="Pre requisite Course ID" /> <br><br>
                Department ID: <input type="text" name="df" placeholder="Department ID" /> <br><br>
                Course Name: <input type="text" name="dl" placeholder="Course Name" /> <br><br>
                Course Credits: <input type="text" name="dbn" placeholder="Course Credits" /> <br><br>
                <input type="submit" class="btn btn-primary" name="edit" value="Edit Course" />
        <form>
        </div>
    </div>


    </div>
    </div>

    <script src="../plugins/js/nav.js"></script>

</body>

</html>
