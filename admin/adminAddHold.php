<?php
include '../connection.php';
session_start();


$student_fetched = false;
if (isset($_POST["submit"])) {

    $hold_id = $_POST["holdId"];
    $hold_type = $_POST["holdType"];
    $sqlMajor = "INSERT INTO hold (`Hold_ID`, `Hold_Type`) VALUES ('".$hold_id."', '".$hold_type."')";
    if ($conn->query($sqlMajor) === TRUE) {
        // echo 'Successfully added minor';
        header('location:adminAddHold.php');
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
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

        <div class="sidenav">
            <aside class="col-xs-12 col-md-12 col-lg-12 side-menu">
                <nav class="left-nav hidden-xs hidden-sm hidden-md">
                    <ul class="nolist">
                        <li class="nolistHighlight image-container">
                            <a href="profile.php">Hello Admin <?php echo $_SESSION["uemail"] ?></a>
                        </li>
                        <li>
                            <a href="#">Register A User</a>
                            <ul class="nolist">
                                <li>
                                    <a href="registerStudent.php">Student</a>
                                </li>
                                <li>
                                    <a href="registerFaculty.php">Faculty</a>
                                </li>
                                <li>
                                    <a href="registerResearcher.php">Researcher</a>
                                </li>

                            </ul>
                        </li>
                        <li>
                            <a href="#"> Department </a>
                            <ul class="nolist">
                                <li>
                                    <a href="addDepartment.php">Add</a>
                                </li>
                                <li>
                                    <a href="editDepartment.php">Edit</a>
                                </li>
                                <li>
                                    <a href="departments.php">Delete</a>
                                </li>

                            </ul>
                        </li>
                        <li>
                            <a href="#/">Course</a>
                            <ul class="nolist">
                                <li>
                                    <a href="addCourse.php">Add</a>
                                </li>
                                <li>
                                    <a href="editCourse.php">Edit</a>
                                </li>
                                <li>
                                    <a href="courses.php">Delete</a>
                                </li>

                            </ul>
                        </li>
                        <li>
                            <a href="#">Student</a>
                            <ul class="nolist">
                                <li>
                                    <a href="adminViewStudent.php">View Student</a>
                                </li>
                                <li>
                                    <a href="adminEditStudent.php">Edit Student</a>
                                </li>
                                <li>
                                    <a href="adminViewStudentCourse.php">View Courses</a>
                                </li>
                                <li>
                                    <a href="adminAddStudentCourse.php">Add Course</a>
                                </li>
                                <li>
                                    <a href="adminViewStudentMajor.php">View Student Major</a>
                                </li>
                                <li>
                                    <a href="adminAddStudentMajor.php">Add Student Major</a>
                                </li>
                                <li>
                                    <a href="viewStudentMinor.php">View Student Minor</a>
                                </li>
                                <li>
                                    <a href="adminAddStudentMinor.php">Add Student Minor</a>
                                </li>
                                <li>
                                    <a href="adminRemoveStudentMinor.php">Remove Student Minor</a>
                                </li>
                                <li>
                                    <a href="adminAddHold.php">Add Hold</a>
                                </li>
                                <li>
                                    <a href="removeHold.php">Remove Hold</a>
                                </li>
                                <li>
                                    <a href="adminViewStudentHold.php">View Student Hold</a>
                                </li>
                                <li>
                                    <a href="adminAddStudentHold.php">Add Student Hold</a>
                                </li>
                                <li>
                                    <a href="adminRemoveStudentHold.php">Remove Student Hold</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Time Slots</a>
                            <ul class="nolist">
                                <li>
                                    <a href="dueDates.php">Due Dates</a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="#">Faculty</a>
                            <ul class="nolist">
                                <li>
                                    <a href="adminViewFacultyDepartment.php">View Faculty Departments</a>
                                </li>
                                <li>
                                    <a href="adminAddFacultyDepartment.php">Add Faculty Departments</a>
                                </li>
                                <!-- <li>
                                    <a href="adminFacultyStudentHistory.php">View Student History</a>
                                </li> -->
                                <li>
                                    <a href="adminFacultyresetPassword.php">Reset Password</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </aside>
        </div>
        <div class="main-page">


            <h3 id="add-student-section"> Add Hold </h3>
            <br>
            <form method="post" action="">
                Hold ID: <input type="text" name="holdId" placeholder="Hold ID" /> <br><br>
                Hold Type: <input type="text" name="holdType" placeholder="Hold Type" /> <br><br>
                <input type="submit" class="btn btn-primary" name="submit" value="Add Hold" />
                <form>

        </div>
    </div>


    </div>
    </div>

    <script src="../plugins/js/nav.js"></script>

</body>

</html>
