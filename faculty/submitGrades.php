<?php
session_start();
include '../connection.php';

$userId = $_SESSION["uId"];

$sqlSection = "Select * from section where Faculty_ID = '".$userId."'";
$querySection = mysqli_query($conn,$sqlSection);

$CourseIds = array();
$crns = array();
$sections = array();

while($row = mysqli_fetch_assoc($querySection)){
    array_push($CourseIds, $row["Course_ID"]);
    array_push($crns, $row["CRN_Num"]);
    array_push($sections, $row);
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Island - Roster</title>

    <link rel="stylesheet" href="../plugins/css/index.css">
    <link rel="stylesheet" href="../plugins/css/navbar.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>
<div id="header">
        <div id="upper-header">
            <!-- <div class="user-name">Hello Faculty</div> -->
            <div class="search-box">
                <!-- <input type="text" placeholder="Search"/> -->
                <!-- <button>Search</button> -->

                <a href="../logout.php"><button>Logout</button></a>
            </div>
        </div>
        <!-- <div id="lower-header">
            <a href="index.php"><div class="navi"> Home </div></a>
            <a href="attendance.php"><div class="navi"> Attendance </div></a>
            <a href="students.php"><div class="navi"> Students </div></a>
           
            <a href="courses.php"><div class="navi"> Courses </div></a>
            <a href="roster.php"><div class="navi"> Roster </div></a>
            
        </div> -->
    </div>

    <div id="main-section">

        <?php
            include 'sidebar.php';
        ?>


        <h2 style="margin-left:50px;"> Select Course </h2>
        <br>
        <table class="view-table">
                <tr>
                    <th>Course ID</th>
                    <th>Course Name</th>
                    <th>Room Number</th>
                    <th>Building</th>
                    <th>Semester</th>
                    <th>Section</th>
                    <th>View</th>
                </tr>
            <?php
                for($i=0; $i<count($sections); $i++){
                    echo '<tr>';
                    echo '<td>'.$sections[$i]["Course_ID"].'</td>';

                    $sqlCourse = "SELECT * FROM course WHERE Course_ID = '".$sections[$i]['Course_ID']."'";
                    $queryCourse = mysqli_query($conn, $sqlCourse);
                    $result = mysqli_fetch_assoc($queryCourse);

                    echo '<td>'.$result["Course_Name"].'</td>';
                    echo '<td>'.$sections[$i]["Room_Num"].'</td>';
                    echo '<td>'.$sections[$i]["Building_Name"].'</td>';
                    echo '<td>'.$sections[$i]["Sem_Term_Year"].'</td>';
                    echo '<td>'.$sections[$i]["Section"].'</td>';
                    echo '<td><a href="submitStudentGrade.php?crn='.$sections[$i]["CRN_Num"].'&cname='.$result["Course_Name"].'">View</a></td>';
                    echo '</tr>';
                }
            ?>

        </table>
    </div>

    <!-- <br>
    <hr>
    <br> -->
    <!-- <div>
        <h3>Add new Course</h3>
        <br>
        <form method="post" action="">
                Course Id: <input type="text" name="dId" placeholder="Course Id" /> <br><br>
                Pre requisite Course ID: <input type="text" name="dn" placeholder="Pre requisite Course ID" /> <br><br>
                Department ID: <input type="text" name="df" placeholder="Department ID" /> <br><br>
                Course Name: <input type="text" name="dl" placeholder="Course Name" /> <br><br>
                Course Credits: <input type="text" name="dbn" placeholder="Course Credits" /> <br><br>
                <input type="submit" name="submit" value="Add" />
                <input type="submit" name="edit" value="Edit" />
        <form>
    </div> -->
</div>

<script src="../plugins/js/nav.js"></script>
</body>
</html>