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
