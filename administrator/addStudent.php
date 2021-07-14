<?php
include '../connection.php';
session_start();

$sqlStudent = "SELECT * FROM student";
$queryStudent = mysqli_query($conn,$sqlStudent);
$rowStudent = mysqli_fetch_assoc($queryStudent);



$student_added = false;
if(isset($_POST["submit"])){
    /////
    $studentUid = $_POST["studentUid"];
    $studentFname = $_POST["studentFname"];
    $studentLname = $_POST["studentLname"];
    $studentDob = $_POST["studentDob"];
    $studentSsn = $_POST["studentSsn"];
    $studentEmail = $_POST["studentEmail"];
    $studentPassword = $_POST["studentPassword"];
    $studentGpa = $_POST["studentGpa"];
    $studentType = $_POST["studentType"];
    $studentFullpart = $_POST["fullPart"];
    $studentStatus = $_POST["studentStatus"];
    $studentMincredit = $_POST["studentMincredit"];
    $studentmaxCredit = $_POST["studentMaxcredit"];

    //Take variables

    $sql = "INSERT INTO users (User_ID, F_Name, L_Name, User_DOB, User_SSN, User_Type) VALUES (".$studentUid.",'".$studentFname."','".$studentLname."','".$studentDob."','".$studentSsn."','Student' )";

    $sql2 = "INSERT INTO login_info (User_ID, User_Email, User_Password, User_Type) VALUES ('".$studentUid."','".$studentEmail."','".$studentPassword."','Student' )";


    $sql3="INSERT INTO student (Student_ID, Student_GPA, Student_Type) VALUES ('".$studentUid."','".$studentGpa."','".$studentType."')";

    $sql4 = "";
    $sql5 = "";

    if($studentType == 'undergraduate'){
        $sql4 = "INSERT INTO undergraduate (Student_ID, Student_Status, Undergrad_Type) VALUES ('".$studentUid."','".$studentStatus."','".$studentFullpart."')";

        if($studentFullpart == 'full time'){
            $sql5 = "INSERT INTO full_time_undergrad (Student_ID, Minimum_Credits, Maximum_Credits) VALUES ('".$studentUid."','".$studentMincredit."','".$studentmaxCredit."')";
        }else{
            $sql5 = "INSERT INTO part_time_undergrad (Student_ID, Minimum_Credits, Maximum_Credits) VALUES ('".$studentUid."','".$studentMincredit."','".$studentmaxCredit."')";
        }
    }else{
        $sql4 = "INSERT INTO graduate (Student_ID, Student_Status, Grad_Type) VALUES ('".$studentUid."','".$studentStatus."','".$studentFullpart."')";

        if($studentFullpart == 'full time'){
            $sql5 = "INSERT INTO full_time_grad (Student_ID, Minimum_Credits, Maximum_Credits) VALUES ('".$studentUid."','".$studentMincredit."','".$studentmaxCredit."')";
        }else{
            $sql5 = "INSERT INTO part_time_grad (Student_ID, Minimum_Credits, Maximum_Credits) VALUES ('".$studentUid."','".$studentMincredit."','".$studentmaxCredit."')";
        }
    }


    if ($conn->query($sql) === TRUE) {
        if ($conn->query($sql2) === TRUE) {

            if ($conn->query($sql3) === TRUE) {

                if ($conn->query($sql4) === TRUE) {

                    if ($conn->query($sql5) === TRUE) {
                        header('location:addStudent.php');
                      } else {
                        echo "Error: " . $sql5 . "<br>" . $conn->error;
                      }
                  } else {
                    echo "Error: " . $sql4 . "<br>" . $conn->error;
                  }
              } else {
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

if(isset($_POST["edit"])){
    $studentUid = $_POST["studentUid"];
    $studentFname = $_POST["studentFname"];
    $studentLname = $_POST["studentLname"];
    $studentDob = $_POST["studentDob"];
    $studentSsn = $_POST["studentSsn"];
    $studentEmail = $_POST["studentEmail"];
    $studentPassword = $_POST["studentPassword"];
    $studentGpa = $_POST["studentGpa"];
    $studentType = $_POST["studentType"];
    $sql6 = "UPDATE student SET Student_GPA = '".$studentGpa."', Student_Type = '".$studentType."' WHERE Student_ID = '".$studentUid."'";
    $sql7 = "UPDATE users SET F_Name = '".$studentFname."', L_Name = '".$studentLname."', User_DOB='".$studentDob."', User_SSN = '".$studentSsn."', User_Type = 'Student' WHERE User_ID = '".$studentUid."'";
    $sql8 = "UPDATE login_info SET User_Email = '".$studentEmail."', User_Password = '".$studentPassword."', User_Type='Student' WHERE User_ID = '".$studentUid."'";
    
    if ($conn->query($sql6) === TRUE) {
        echo 'Successfully updated student';
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

    } else {
      echo "Error: " . $sql6 . "<br>" . $conn->error;
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
</head>
<body>
<div id="header">
        <div id="upper-header">
            <div class="user-name">Hello admin</div>
            <div class="search-box">
                <input type="text" placeholder="Search"/>
                <button>Search</button>
            </div>
        </div>
        <div id="lower-header">
            <a href="index.php"><div class="navi"> Home </div></a>
            <a href="departments.php"><div class="navi"> Departments </div></a>
            <a href="addStudent.php"><div class="navi"> Students </div></a>
            <a href="enrolled.php"><div class="navi"> Academics </div></a>
            <a href="courses.php"><div class="navi"> Courses </div></a>
            <a href="faculty.php"><div class="navi"> Faculty </div></a>
            <a href="researcher.php"><div class="navi"> Researcher </div></a>
        </div>
    </div>

    <div id="main-section">

    <h2 style="margin-left:50px;"> Students </h2>
        <br>
        <br>

        <p>Go to <a href="#add-student-section">add student</a> <br>
        <br>
        <table class="view-table">
                <tr>
                    <th>Student ID</th>
                    <th>Student GPA</th>
                    <th>Student Type</th>
                    <th>Drop</th>
                </tr>
            <?php
                while($rowStudent = mysqli_fetch_assoc($queryStudent)){
                    echo '<tr>';
                    echo '<td>'.$rowStudent["Student_ID"].'</td>';
                    echo '<td>'.$rowStudent["Student_GPA"].'</td>';
                    echo '<td>'.$rowStudent["Student_Type"].'</td>';
                    echo '<td><a href="deleteStudent.php?userId='.$rowStudent["Student_ID"].'">Delete</a></td>';
                    echo '</tr>';
                }
            ?>

        </table>


     <br>
     <br>
     <hr>
     <br>
     <br>

     <h3 id="add-student-section"> Add Student </h3> 
     <br>  
    <?php 
    if($student_added){
        echo "Student Added Successfully";
    }
    ?>
    <form method="post" action="">
        <input type="text" placeholder="Student User ID" name="studentUid" /> <br><br>
        <input type="text" placeholder="Student First Name" name="studentFname" /> <br><br>
        <input type="text" placeholder="Student Last Name" name="studentLname" /> <br><br>
        <input type="date" placeholder="Student DOB" name="studentDob" /> <br><br>
        <input type="text" placeholder="Student SSN" name="studentSsn" /> <br><br>
        <input type="text" placeholder="Student Email" name="studentEmail" /> <br><br>
        <input type="text" placeholder="Student Password" name="studentPassword" /> <br><br>
        <input type="text" placeholder="Student GPA" name="studentGpa" /> <br><br>
        <select name="studentType">
            <option value="undergraduate">undergraduate</option>
            <option value="graduate">graduate</option>
        </select>

        <br><br>

        <select name="fullPart">
            <option value="Fulltime_Undergrad">full time</option>
            <option value="Parttime_Undergrad">part time</option>
        </select>

        <br><br>

        <input type="text" placeholder="Student Status" name="studentStatus" /><br><br>
        <input type="text" placeholder="Student Minimum Credit" name="studentMincredit" /><br><br>
        <input type="text" placeholder="Student Maximum Credit" name="studentMaxcredit" /><br><br>
        
        
        <input type="submit" name="submit" value="Add Student"/>
        <input type="submit" name="edit" value="Edit" />

        <br><br>
        <br><br>
    </form>

    </div>
</div>
</body>
</html>