<?php
session_start();
include '../connection.php';

$userId = $_SESSION["user_id"]];

$sql = "SELECT * FROM users WHERE User_Type = 'Researcher'";
$query = mysqli_query($conn,$sql);

$student_added = false;

if(isset($_POST["submit"])){
    $studentUid = $_POST["studentUid"];
    $studentFname = $_POST["studentFname"];
    $studentLname = $_POST["studentLname"];
    $studentDob = $_POST["studentDob"];
    $studentSsn = $_POST["studentSsn"];
    $studentEmail = $_POST["studentEmail"];
    $studentPassword = $_POST["studentPassword"];

    //Take variables

    $sql = "INSERT INTO users (User_ID, F_Name, L_Name, User_DOB, User_SSN, User_Type) VALUES (".$studentUid.",'".$studentFname."','".$studentLname."','".$studentDob."','".$studentSsn."','Researcher' )";

    $sql2 = "INSERT INTO login_info (User_ID, User_Email, User_Password, User_Type) VALUES ('".$studentUid."','".$studentEmail."','".$studentPassword."','Researcher' )";

    $sql3="INSERT INTO researcher (Researcher_ID) VALUES ('".$studentUid."')";


    if ($conn->query($sql) === TRUE) {
        if ($conn->query($sql2) === TRUE) {
            if ($conn->query($sql3) === TRUE) {
              } else {
                header('location:researcher.php');
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

    $sql7 = "UPDATE users SET F_Name = '".$studentFname."', L_Name = '".$studentLname."', User_DOB='".$studentDob."', User_SSN = '".$studentSsn."', User_Type = 'Researcher' WHERE User_ID = '".$studentUid."'";
    $sql8 = "UPDATE login_info SET User_Email = '".$studentEmail."', User_Password = '".$studentPassword."', User_Type='Researcher' WHERE User_ID = '".$studentUid."'";

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
    <title>Island - Enrolled</title>

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
        <h2 style="margin-left:50px;"> Researchers </h2>
        <br>
        <table class="view-table">
                <tr>
                    <th>User ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>User Date Of Birth</th>
                    <th>User SSN</th>
                    <th>Drop</th>
                </tr>
            <?php
                while($row = mysqli_fetch_assoc($query)){
                    echo '<tr>';
                    echo '<td>'.$row["User_ID"].'</td>';
                    echo '<td>'.$row["F_Name"].'</td>';
                    echo '<td>'.$row["L_Name"].'</td>';
                    echo '<td>'.$row["User_DOB"].'</td>';
                    echo '<td>'.$row["User_SSN"].'</td>';
                    echo '<td><a href="deleteResearcher.php?userId='.$row["User_ID"].'">Delete</a></td>';
                    echo '</tr>';
                }
            ?>

        </table>

        <br>
     <br>
     <hr>
     <br>
     <br>

     <h3> Add Researcher </h3>
     <br>
    <?php
    if($student_added){
        echo "Student Added Successfully";
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

        <input type="submit" name="submit" value="Add Researcher"/>
        <input type="submit" name="edit" value="Edit" />

        <br><br>
    </form>
    </div>
</div>
</body>
</html>
