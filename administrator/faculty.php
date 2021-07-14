<?php
session_start();
include '../connection.php';

$userId = $_SESSION["user_id"]];

$sql = "SELECT * FROM faculty";
$query = mysqli_query($conn,$sql);


$faculty_added = false;
if(isset($_POST["submit"])){
    /////user
    $facultyUid = $_POST["facultyUid"];
    $facultyFname = $_POST["facultyFname"];
    $facultyLname = $_POST["facultyLname"];
    $facultyDob = $_POST["facultyDob"];
    $facultySsn = $_POST["facultySsn"];

    //login
    $facultyEmail = $_POST["facultyEmail"];
    $facultyPassword = $_POST["facultyPassword"];

    //faculty
    $facultyID = $_POST["facultyID"];
    $facultyRank = $_POST["facultyRank"];
    $facultyFullpart = $_POST["fullPart"];


    //4th
    $numsec = $_POST["numsec"];
    $seclim = $_POST["seclim"];

    $sql2 = "INSERT INTO users (User_ID, F_Name, L_Name, User_DOB, User_SSN, User_Type) VALUES (".$facultyUid.",'".$facultyFname."','".$facultyLname."','".$facultyDob."','".$facultySsn."','Faculty' )";

    $sql3 = "INSERT INTO login_info (User_ID, User_Email, User_Password, User_Type) VALUES ('".$facultyUid."','".$facultyEmail."','".$facultyPassword."','Faculty' )";

    $sql4 = "INSERT INTO faculty (`Faculty_ID`, `Faculty_Rank`, `Faculty_Type`) VALUES ('".$facultyID."','".$facultyRank."','".$facultyFullpart."')";

    $sql5 = '';
    if($facultyFullpart == 'Part_T_Faculty'){
        $sql5 = "INSERT INTO Part_T_Faculty (`Faculty_ID`, `Faculty_Rank`, `Faculty_Type`) VALUES ('".$facultyID."','".$numsec."','".$seclim."')";
    }else{
        $sql5 = "INSERT INTO Full_T_Faculty (`Faculty_ID`, `Faculty_Rank`, `Faculty_Type`) VALUES ('".$facultyID."','".$numsec."','".$seclim."')";
    }

    if ($conn->query($sql2) === TRUE) {

        if ($conn->query($sql3) === TRUE) {

            if ($conn->query($sql4) === TRUE) {

                if ($conn->query($sql5) === TRUE) {
                  } else {
                    header('location:faculty.php');
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
}



if(isset($_POST["edit"])){
    /////user
    $facultyUid = $_POST["facultyUid"];
    $facultyFname = $_POST["facultyFname"];
    $facultyLname = $_POST["facultyLname"];
    $facultyDob = $_POST["facultyDob"];
    $facultySsn = $_POST["facultySsn"];

    //login
    $facultyEmail = $_POST["facultyEmail"];
    $facultyPassword = $_POST["facultyPassword"];

    //faculty
    $facultyID = $_POST["facultyID"];
    $facultyRank = $_POST["facultyRank"];
    $facultyFullpart = $_POST["fullPart"];


    //4th
    $numsec = $_POST["numsec"];
    $seclim = $_POST["seclim"];


    $sql6 = "UPDATE faculty SET Faculty_Rank = '".$facultyRank."', Faculty_Type = '".$facultyFullpart."' WHERE Faculty_ID = '".$facultyUid."'";
    $sql7 = "UPDATE users SET F_Name = '".$facultyFname."', L_Name = '".$facultyLname."', User_DOB='".$facultyDob."', User_SSN = '".$facultyDob."', User_Type = 'Faculty' WHERE User_ID = '".$facultyID."'";
    $sql8 = "UPDATE login_info SET User_Email = '".$facultyEmail."', User_Password = '".$facultyPassword."', User_Type='Faculty' WHERE User_ID = '".$facultyUid."'";

    if ($conn->query($sql6) === TRUE) {
        echo 'Successfully updated faculty';
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
    <title>Island - Faculty</title>

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
        <h2 style="margin-left:50px;"> Faculty </h2>
        <br>
        <table class="view-table">
                <tr>
                    <th>Faculty ID</th>
                    <th>Faculty Rank</th>
                    <th>Faculty Type</th>
                    <th>Drop</th>
                </tr>
            <?php
                while($row = mysqli_fetch_assoc($query)){
                    echo '<tr>';
                    echo '<td>'.$row["Faculty_ID"].'</td>';
                    echo '<td>'.$row["Faculty_Rank"].'</td>';
                    echo '<td>'.$row["Faculty_Type"].'</td>';
                    echo '<td><a href="deleteFaculty.php?fId='.$row["Faculty_ID"].'">Delete</a></td>';
                    echo '</tr>';
                }
            ?>

        </table>
    </div>


    <br>
    <hr>
    <br>
    <div>
        <h3>Add new Faculty</h3>
        <br>
        <form method="post" action="">
        <input type="text" placeholder="Faculty User ID" name="facultyUid" /><br><br>
        <input type="text" placeholder="Faculty First Name" name="facultyFname" /><br><br>
        <input type="text" placeholder="Faculty Last Name" name="facultyLname" /><br><br>
        <input type="date" placeholder="Faculty DOB" name="facultyDob" /><br><br>
        <input type="text" placeholder="Faculty SSN" name="facultySsn" /><br><br>
        <input type="text" placeholder="Faculty Email" name="facultyEmail" /><br><br>
        <input type="text" placeholder="Faculty Password" name="facultyPassword" /><br><br>
        <input type="text" placeholder="Faculty ID" name="facultyID" /><br><br>
        <input type="text" placeholder="Faculty Rank" name="facultyRank" /><br><br>
        <input type="text" placeholder="Number sections" name="numsec" /><br><br>
        <input type="text" placeholder="Section Limit" name="seclim" /><br><br>



        <select name="fullPart">
            <option value="Part_T_Faculty">Part time</option>
            <option value="Full_T_Faculty">Full time</option>
        </select>

        <br><br>
        <input type="submit" name="submit" value="Add faculty"/>
        <input type="submit" name="edit" value="Edit"/>
        <br><br>
    </form>
    </div>
</div>
</body>
</html>
