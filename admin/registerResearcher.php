<?php
include '../connection.php';
session_start();

$sqlStudent = "SELECT * FROM student";
$queryStudent = mysqli_query($conn, $sqlStudent);
$rowStudent = mysqli_fetch_assoc($queryStudent);



$student_added = false;

if (isset($_POST["submit"])) {
    $studentUid = $_POST["studentUid"];
    $studentFname = $_POST["studentFname"];
    $studentLname = $_POST["studentLname"];
    $studentDob = $_POST["studentDob"];
    $studentSsn = $_POST["studentSsn"];
    $studentEmail = $_POST["studentEmail"];
    $studentPassword = $_POST["studentPassword"];

    //Take variables

    $sql = "INSERT INTO users (User_ID, F_Name, L_Name, User_DOB, User_SSN, User_Type) VALUES (" . $studentUid . ",'" . $studentFname . "','" . $studentLname . "','" . $studentDob . "','" . $studentSsn . "','Researcher' )";

    $sql2 = "INSERT INTO login_info (User_ID, User_Email, User_Password, User_Type) VALUES ('" . $studentUid . "','" . $studentEmail . "','" . $studentPassword . "','Researcher' )";

    $sql3 = "INSERT INTO researcher (Researcher_ID) VALUES ('" . $studentUid . "')";


    if ($conn->query($sql) === TRUE) {
        if ($conn->query($sql2) === TRUE) {
            if ($conn->query($sql3) === TRUE) {
            } else {
                // header('location:registerResearcher.php');
                echo '<script>alert("Researcher Registered Successfully")</script>';
                echo "Error: " . $sql3 . "<br>" . $conn->error;
            }
        } else {
            echo "Error: " . $sql2 . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    //Store in data
    $student_added = true;
}

if (isset($_POST["edit"])) {
    $studentUid = $_POST["studentUid"];
    $studentFname = $_POST["studentFname"];
    $studentLname = $_POST["studentLname"];
    $studentDob = $_POST["studentDob"];
    $studentSsn = $_POST["studentSsn"];
    $studentEmail = $_POST["studentEmail"];
    $studentPassword = $_POST["studentPassword"];

    $sql7 = "UPDATE users SET F_Name = '" . $studentFname . "', L_Name = '" . $studentLname . "', User_DOB='" . $studentDob . "', User_SSN = '" . $studentSsn . "', User_Type = 'Researcher' WHERE User_ID = '" . $studentUid . "'";
    $sql8 = "UPDATE login_info SET User_Email = '" . $studentEmail . "', User_Password = '" . $studentPassword . "', User_Type='Researcher' WHERE User_ID = '" . $studentUid . "'";

    if ($conn->query($sql7) === TRUE) {
        echo 'Successfully updated users';

        if ($conn->query($sql8) === TRUE) {
            echo 'Successfully updated login_info';
        } else {
            echo "Error: " . $sql8 . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $sql7 . "<br>" . $conn->error;
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Island - Add Student</title>

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


            <h3 id="add-student-section"> Add Researcher </h3>
            <br>
            <?php

            if ($student_added) {
                echo "Researcher Added Successfully";
            }
            ?>
            <form method="post" action="">
                <input type="text" placeholder="Researcher User ID" name="studentUid" /> <br><br>
                <input type="text" placeholder="Researcher First Name" name="studentFname" /> <br><br>
                <input type="text" placeholder="Researcher Last Name" name="studentLname" /> <br><br>
                <input type="date" placeholder="Researcher DOB" name="studentDob" /> <br><br>
                <input type="text" placeholder="Researcher SSN" name="studentSsn" /> <br><br>
                <input type="text" placeholder="Researcher Email" name="studentEmail" /> <br><br>
                <input type="text" placeholder="Researcher Password" name="studentPassword" /> <br><br>

                <input type="submit" name="submit" value="Add Researcher" />
                <input type="submit" name="edit" value="Edit" />

                <br><br>
            </form>

        </div>
    </div>


    </div>
    </div>

    <script src="../plugins/js/nav.js"></script>

</body>

</html>
