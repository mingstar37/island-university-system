<?php
include '../connection.php';
session_start();

$currstudentid = "";
$student_fetched = false;
if(isset($_POST["submit"])){
    $sId = $_POST["studentId"];

    $sqlStudent = "SELECT * FROM student WHERE Student_ID='".$sId."'";
    $queryStudent = mysqli_query($conn,$sqlStudent);
    $rowStudent = mysqli_fetch_assoc($queryStudent);

    $sqlStudent2 = "SELECT * FROM users WHERE User_ID='".$sId."'";
    $queryStudent2 = mysqli_query($conn,$sqlStudent2);
    $rowStudent2 = mysqli_fetch_assoc($queryStudent2);

    $currstudentid = $sId;
    $currstudentgpa = $rowStudent["Student_GPA"];
    $currstudenttype = $rowStudent["Student_Type"];
    $currentstudentfname = $rowStudent2["F_Name"];
    $currentstudentlname = $rowStudent2["L_Name"];
    $currentstudentdob = $rowStudent2["User_DOB"];
    $currentstudentssn = $rowStudent2["User_SSN"];

    $student_fetched = true;
}


if(isset($_POST["editSubmit"])){
    $sId = $_POST["studentId"];
    $sfname = $_POST["studentfname"];
    $slname = $_POST["studentlname"];
    $sdob = $_POST["studentdob"];
    $sssn = $_POST["studentssn"];
    $sgpa = $_POST["studentgpa"];


    $sqlEdit = "UPDATE users SET F_Name='".$sfname."', L_Name='".$slname."', User_DOB='".$sdob."', User_SSN='".$sssn."' WHERE User_ID='".$sId."'";

    $sqlEdit2 = "UPDATE student SET Student_GPA='".$sgpa."' WHERE Student_ID='".$sId."'";

    if ($conn->query($sqlEdit) === TRUE) {
        if ($conn->query($sqlEdit2) === TRUE) {
            echo '<script>alert("Student Updated Successfully")</script>';
            $currstudentid = $sId;
//            $currstudentgpa = $sgpa;
//            $currentstudentfname = $sfname;
//            $currentstudentlname = $slname;
//            $currentstudentdob = $sdob;

            $sqlStudent = "SELECT * FROM student WHERE Student_ID='".$sId."'";
            $queryStudent = mysqli_query($conn,$sqlStudent);
            $rowStudent = mysqli_fetch_assoc($queryStudent);

            $sqlStudent2 = "SELECT * FROM users WHERE User_ID='".$sId."'";
            $queryStudent2 = mysqli_query($conn,$sqlStudent2);
            $rowStudent2 = mysqli_fetch_assoc($queryStudent2);

            $currstudentid = $sId;
            $currstudentgpa = $rowStudent["Student_GPA"];
            $currstudenttype = $rowStudent["Student_Type"];
            $currentstudentfname = $rowStudent2["F_Name"];
            $currentstudentlname = $rowStudent2["L_Name"];
            $currentstudentdob = $rowStudent2["User_DOB"];
            $currentstudentssn = $rowStudent2["User_SSN"];

            $student_fetched = true;

            // header('location:adminEditStudent.php');
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
    <title>Island - Admin Edit Student</title>

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


            <h3 id="add-student-section"> Edit Student </h3>
            <br>
           <form method="post" action="">
                Enter Student ID: <input type="text" name="studentId" value="<?php echo $currstudentid; ?>" placeholder="Student Id" /> <br><br>
                <input type="submit" class="btn btn-primary" name="submit" value="View Student" />
        <form>

        <br>
        <hr>
        <br>
        <?php
            if($student_fetched == true){

        ?>

        <form method="post" action="">
            Student ID: <input type="text" name="studentId" value="<?php echo $currstudentid?>" disabled/><br><br>
            Student First Name: <input type="text" name="studentfname" value="<?php echo $currentstudentfname?>" /><br><br>
            Student Last Name: <input type="text" name="studentlname" value="<?php echo $currentstudentlname?>" /><br><br>
            Student DOB: <input type="date" name="studentdob" value="<?php echo $currentstudentdob?>" /><br><br>
            Student SSN: <input type="text" name="studentssn" value="<?php echo $currentstudentssn?>" /><br><br>
            Student GPA: <input type="text" name="studentgpa" value="<?php echo $currstudentgpa?>" /><br> <br><br>

            <input type="submit" name="editSubmit" value="Edit Student" class="btn btn-primary" />
        </form>
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
