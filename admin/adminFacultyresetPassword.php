<?php
include '../connection.php';
session_start();

$deptFaculty = array();
$student_fetched = false;
if(isset($_POST["submit"])){
    $facultyId = $_POST["facultyId"];
    $password = $_POST["password"];
    $cPassword = $_POST["cpassword"];

    if($password == $cPassword){
        $sqlFaculty = "UPDATE login_info SET User_Password='".$password."' WHERE User_ID='".$facultyId."'";
        if ($conn->query($sqlFaculty) === TRUE) {
            header('location:adminFacultyResetPassword.php');
        } else {
            echo "Error: " . $sqlFaculty . "<br>" . $conn->error;
        }
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
    <title>Island - Admin</title>

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


            <h3 id="add-student-section"> Reset Faculty Password </h3>
            <br>
           <form method="post" action="">
                Enter Faculty ID : <input type="text" name="facultyId" placeholder="Enter Faculty Id" /> <br><br>
                Enter New Password: <input type="password" name="password" placeholder="Enter Password" /> <br><br>
                Confirm Password: <input type="password" name="cpassword" placeholder="Confirm Password" /> <br><br>
                <input type="submit" class="btn btn-primary" name="submit" value="Reset Password" />
        <form>

        <br>
        <hr>
        <br>
        <?php
            if($student_fetched == true){
                ?>
                <table class="view-table">
                <tr>
                    <th>Department ID</th>
                    <th>Department Name</th>
                    <th>Building Name</th>
                </tr>
            <?php
            for($i=0; $i<count($deptFaculty); $i++){
                echo '<tr>';
                echo '<td>'.$deptFaculty[$i]["Dept_ID"].'</td>';
                echo '<td>'.$deptFaculty[$i]["Dept_Name"].'</td>';
                echo '<td>'.$deptFaculty[$i]["Building_Name"].'</td>';
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
