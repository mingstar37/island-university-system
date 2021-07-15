<?php
include '../connection.php';
session_start();


$student_fetched = false;
if(isset($_POST["submit"])){
    $sId = $_POST["studentId"];

    $sqlStudent = "SELECT * FROM student_holds WHERE Student_ID='".$sId."'";
    $queryStudent = mysqli_query($conn,$sqlStudent);
    // $rowStudent = mysqli_fetch_assoc($queryStudent);


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
        <!-- <h1 id="banner"> Island University - Admin </h1> -->

        <?php
        include "sidenav.php";
        ?>
        <div class="main-page">


            <h3 id="add-student-section"> Remove Student Holds </h3>
            <br>
           <form method="post" action="">
                Enter Student ID: <input type="text" name="studentId" placeholder="Student Id" /> <br><br>
                <input type="submit" class="btn btn-primary" name="submit" value="View Student Holds" />
        <form>

        <br>
        <hr>
        <br>
        <?php
            if($student_fetched == true){
                ?>
                <table class="view-table">
                <tr>
                    <th>Student ID</th>
                    <th>Hold Date</th>
                    <th>Hold Type</th>
                    <th>Drop</th>
                </tr>
            <?php
            while($rowStudent = mysqli_fetch_assoc($queryStudent)){
                echo '<tr>';
                echo '<td>'.$rowStudent["Student_ID"].'</td>';
                echo '<td>'.$rowStudent["Hold_Date"].'</td>';
                echo '<td>'.$rowStudent["Hold_Type"].'</td>';
                echo '<td><a href="removeStudentHold.php?studentId='.$rowStudent["Student_ID"].'&holdDate='.$rowStudent["Hold_Date"].'&holdType='.$rowStudent["Hold_Type"].'">Remove</a></td>';
                echo '</tr>';
            }

            ?>

        </table>
                <?php
            }
        ?>
        </div>
    </div>


    </div>
    </div>

    <script src="../plugins/js/nav.js"></script>

</body>

</html>