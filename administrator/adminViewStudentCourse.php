<?php
include '../connection.php';
session_start();


$student_fetched = false;
if(isset($_POST["submit"])){
    $sId = $_POST["studentId"];

    $sqlStudent = "SELECT * FROM enrollment WHERE Student_ID='".$sId."'";
    $queryStudent = mysqli_query($conn,$sqlStudent);

    $student_fetched = true;
}


if(isset($_POST["editSubmit"])){
    $sId = $_POST["studentId"];
    $sfname = $_POST["studentfname"];
    $slname = $_POST["studentlname"];
    $sdob = $_POST["studentdob"];
    $sssn = $_POST["studentssn"];
    $sgpa = $_POST["studentgpa"];


    $sqlEdit = "UPDATE users SET F_Name='".$sfname."', L_Name='".$slname."', User_DOB='".$sdob."', User_SSN='".$sssn."' WHERE USER_ID='".$sId."'";

    $sqlEdit2 = "UPDATE student SET Student_GPA='".$sgpa."' WHERE Student_ID='".$sId."'";

    if ($conn->query($sqlEdit) === TRUE) {
        if ($conn->query($sqlEdit2) === TRUE) {
            header('location:adminEditStudent.php');
          } else {
            echo "Error: " . $sqlEdit2 . "<br>" . $conn->error;
          }
      } else {
        echo "Error: " . $sqlEdit . "<br>" . $conn->error;
      }


    $student_fetched = true;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Island - Admin View Course</title>

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


            <h3 id="add-student-section"> View Student Courses </h3>
            <br>
           <form method="post" action="">
                Enter Student ID: <input type="text" name="studentId" placeholder="Student Id" /> <br><br>
                <input type="submit" class="btn btn-primary" name="submit" value="View Student Courses" />
        <form>

        <br>
        <hr>
        <br>
        <?php
            if($student_fetched == true){
        ?>

                <table class="view-table">
                <tr>
                    <th>CRN Number</th>
                    <th>Date Enrolled</th>
                    <th>Letter Grade</th>
                </tr>
            <?php
                while($rowStudent = mysqli_fetch_assoc($queryStudent)){
                    echo '<tr>';
                    echo '<td>'.$rowStudent["CRN_Num"].'</td>';
                    echo '<td>'.$rowStudent["Date_Enrolled"].'</td>';
                    echo '<td>'.$rowStudent["Letter_Grade"].'</td>';
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
