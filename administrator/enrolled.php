<?php
session_start();
include '../connection.php';

$userId = $_SESSION["user_id"];

$sql = "SELECT * FROM enrollment";
$query = mysqli_query($conn,$sql);
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
        <h2 style="margin-left:50px;"> Enrolled Students </h2>
        <br>
        <table class="view-table">
                <tr>
                    <th>Student ID</th>
                    <th>CRN number</th>
                    <th>Date of Enrollment</th>
                    <th>Grade</th>
                </tr>
            <?php
                while($row = mysqli_fetch_assoc($query)){
                    echo '<tr>';
                    echo '<td>'.$row["Student_ID"].'</td>';
                    echo '<td>'.$row["CRN_Num"].'</td>';
                    echo '<td>'.$row["Date_Enrolled"].'</td>';
                    echo '<td>'.$row["Letter_Grade"].'</td>';
                    echo '</tr>';
                }
            ?>

        </table>
    </div>
</div>
</body>
</html>
