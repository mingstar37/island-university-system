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
    <div id="header">
        <div id="upper-header">
            <!-- <div class="user-name"><a href="profile.php">Hello admin</a></div> -->
            <div class="search-box">
                <!-- <input type="text" placeholder="Search" />
                <button>Search</button> -->

                <a href="../logout.php"><button>Logout</button></a>
            </div>
        </div>
        <div id="lower-header">
            <a href="index.php">
                <div class="navi"> Home </div>
            </a>
            <a href="departments.php">
                <div class="navi"> Departments </div>
            </a>
            <a href="addStudent.php">
                <div class="navi"> Students </div>
            </a>
            <a href="enrolled.php">
                <div class="navi"> Academics </div>
            </a>
            <a href="courses.php">
                <div class="navi"> Courses </div>
            </a>
            <a href="faculty.php">
                <div class="navi"> Faculty </div>
            </a>
            <a href="researcher.php">
                <div class="navi"> Researcher </div>
            </a>
        </div>
    </div>


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