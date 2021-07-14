<?php
session_start();
include '../connection.php';

$userId = $_SESSION["user_id"]];

$sqlSection = "Select * from section where Faculty_ID = '".$userId."'";
$querySection = mysqli_query($conn,$sqlSection);

$CourseIds = array();
$crns = array();
$periods = array();

while($row = mysqli_fetch_assoc($querySection)){
    array_push($CourseIds, $row["Course_ID"]);
    array_push($crns, $row);

    $sqlTimes = "SELECT * FROM time_slot_period WHERE Time_Slot_ID = '".$row["Time_Slot_ID"]."'";
    $querytimes = mysqli_query($conn, $sqlTimes);
    $rowTimes = mysqli_fetch_assoc($querytimes);
    array_push($periods, $rowTimes["Period_No"]);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Island - Courses</title>

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
        <h2 style="margin-left:50px;"> Courses </h2>
        <br>
        <table class="view-table">
                <tr>
                    <th>Course ID</th>
                    <!-- <th>Prerequisite Course Id</th> -->
                    <th>Building</th>
                    <th>Course Name</th>
                    <th>Course Credits</th>
                    <th>Faculty</th>
                    <th>Room Number</th>
                    <th>Semester</th>
                    <th>Section</th>
                    <th>Days</th>
                    <th>Time</th>
                </tr>
            <?php
                for($i=0; $i<count($CourseIds); $i++){
                    $course = $CourseIds[$i];
                    $sql = "SELECT * FROM course WHERE Course_ID = '".$course."'";
                    $query1 = mysqli_query($conn,$sql);
                    $result = mysqli_fetch_assoc($query1);
                    echo '<tr>';
                    echo '<td>'.$result["Course_ID"].'</td>';
                    // echo '<td>'.$result["Prereq_Course_ID"].'</td>';
                    echo '<td>'.$crns[$i]["Building_Name"].'</td>';
                    echo '<td>'.$result["Course_Name"].'</td>';
                    echo '<td>'.$result["Course_Credits"].'</td>';


                    $sqlFac = "SELECT * FROM users where User_ID = '".$crns[$i]["Faculty_ID"]."'";
                    $query = mysqli_query($conn, $sqlFac);
                    $rowFac = mysqli_fetch_assoc($query);

                    echo '<td>'.$rowFac["F_Name"].' '.$rowFac["L_Name"].'</td>';
                    echo '<td>'.$crns[$i]["Room_Num"].'</td>';
                    echo '<td>'.$crns[$i]["Sem_Term_Year"].'</td>';
                    echo '<td>'.$crns[$i]["Section"].'</td>';

                    $days = array();
                    $sqlDays = "SELECT * FROM time_slot_day where Time_Slot_ID = '".$crns[$i]["Time_Slot_ID"]."'";
                    $queryDays = mysqli_query($conn, $sqlDays);
                    while($rowDays = mysqli_fetch_assoc($queryDays)){
                        array_push($days, $rowDays["Week_Day"]);
                    }

                    $List = implode(', ', $days);
                    echo '<td>';
                    print_r($List);
                    echo '</td>';


                    $sqlPeriod = "SELECT * FROM period WHERE Period_No = '".$periods[$i]."'";
                    $queryPeriod = mysqli_query($conn, $sqlPeriod);
                    $rowPeriod = mysqli_fetch_assoc($queryPeriod);

                    echo '<td>'.$rowPeriod["Start_Time"].' '.$rowPeriod["End_Time"].'</td>';


                    echo '</tr>';
                }
            ?>

        </table>
    </div>


</div>
</body>
</html>
