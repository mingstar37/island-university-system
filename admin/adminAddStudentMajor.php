<?php
include '../connection.php';
session_start();


$student_fetched = false;
$sType = [];
$sId = '';
if (isset($_POST["submit"])) {
    $sId = $_POST["studentId"];
    $sType = !empty($_POST["type"]) ? $_POST["type"] : [];

    $_SESSION["sId"] = $sId;

    if (!empty($sType)) {
        $_SESSION["sType"] = implode(',', $sType );
    } else {
        $_SESSION["sType"] = '';
    }

    // $sqlStudent = "SELECT * FROM student_major WHERE Student_ID='".$sId."'";
    // $queryStudent = mysqli_query($conn,$sqlStudent);
    // $rowStudent = mysqli_fetch_assoc($queryStudent);

    // $majorId = $rowStudent["Major_ID"];

    // $sqlMajor = "SELECT * FROM major WHERE Major_ID='".$majorId."'";
    // $queryMajor = mysqli_query($conn,$sqlMajor);
    // $rowMajor = mysqli_fetch_assoc($queryMajor);


    $sqlMajor = "SELECT * FROM major";
    $queryMajor = mysqli_query($conn, $sqlMajor);



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


            <h3 id="add-student-section"> Add Student Major </h3>
            <br>
            <form method="post" action="">
                Enter Student ID: <input type="text" name="studentId" value="<?php echo $sId; ?>" required placeholder="Student Id" /> <br><br>
                <input type="checkbox" name="type[]" value="First Major" <?php if (in_array('First Major', $sType)) echo 'checked'; ?>/>&nbsp; First Major<br/><br/>
                <input type="checkbox" name="type[]" value="Second Major"<?php if (in_array('Second Major', $sType)) echo 'checked'; ?>/>&nbsp; Second Major<br/><br/>
                <input type="submit" class="btn btn-primary" name="submit" value="Add Student Major" />
                <form>

                    <br>
                    <hr>
                    <br>
                    <?php
                    if ($student_fetched == true) {
                    ?>
                        <table class="view-table">
                            <tr>
                                <th>Major ID</th>
                                <th>Major Name</th>
                                <th>Department ID</th>
                                <th> Add </th>
                            </tr>
                            <?php
                            while ($rowMajor = mysqli_fetch_assoc($queryMajor)) {
                                echo '<tr>';
                                echo '<td>' . $rowMajor["Major_ID"] . '</td>';
                                echo '<td>' . $rowMajor["Major_Name"] . '</td>';
                                echo '<td>' . $rowMajor["Dept_ID"] . '</td>';
                                echo '<td><a href="addStudentMajor.php?studentId='.$_SESSION["sId"].'&type='.$_SESSION["sType"] . '&majorId='.$rowMajor["Major_ID"].'">Add</a></td>';
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
