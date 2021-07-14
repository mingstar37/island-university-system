<?php
include '../connection.php';
session_start();


$student_fetched = false;
if(isset($_POST["submit"])){
    $sId = $_POST["studentId"];

    $sqlStudent = "SELECT * FROM student_minor WHERE Student_ID='".$sId."'";
    $queryStudent = mysqli_query($conn,$sqlStudent);
    $rowStudent = mysqli_fetch_assoc($queryStudent);

    $majorId = $rowStudent["Minor_ID"];

    $sqlMajor = "SELECT * FROM minor WHERE Minor_ID='".$majorId."'";
    $queryMajor = mysqli_query($conn,$sqlMajor);
    $rowMajor = mysqli_fetch_assoc($queryMajor);



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


            <h3 id="add-student-section"> View Student Minor </h3>
            <br>
           <form method="post" action="">
                Enter Student ID: <input type="text" name="studentId" placeholder="Student Id" /> <br><br>
                <input type="submit" class="btn btn-primary" name="submit" value="View Student Minor" />
        <form>

        <br>
        <hr>
        <br>
        <?php
            if($student_fetched == true){
                ?>
                <table class="view-table">
                <tr>
                    <th>Minor ID</th>
                    <th>Minor Name</th>
                    <th>Department ID</th>
                    <th>Type</th>
                </tr>
            <?php
                    echo '<tr>';
                    echo '<td>'.$rowMajor["Minor_ID"].'</td>';
                    echo '<td>'.$rowMajor["Minor_Name"].'</td>';
                    echo '<td>'.$rowMajor["Dept_ID"].'</td>';
                    echo '<td>'.$rowStudent["Type"].'</td>';
                    echo '</tr>';
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
