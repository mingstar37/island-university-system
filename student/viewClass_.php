<?php
session_start();
include '../connection.php';


$userId = $_SESSION["user_id"];
$sql = "SELECT * FROM enrollment WHERE Student_ID = '".$userId."' and Date_Enrolled LIKE '%2021%'";
$query = mysqli_query($conn, $sql);

$crns = array();
$sections = array();
$faculty = array();
$courseNames = array();
$periods = array();

while($row = mysqli_fetch_assoc($query)){
    array_push($crns, $row["CRN_Num"]);
    $crn = $row["CRN_Num"];

    $sql2 = "SELECT * FROM section WHERE CRN_Num = '".$crn."'";
    $query2 = mysqli_query($conn, $sql2);

    while($row2 = mysqli_fetch_assoc($query2)){
        array_push($sections, $row2);

        $sqlTimes = "SELECT * FROM time_slot_period WHERE Time_Slot_ID = '".$row2["Time_Slot_ID"]."'";
        $querytimes = mysqli_query($conn, $sqlTimes);
        $rowTimes = mysqli_fetch_assoc($querytimes);
        array_push($periods, $rowTimes["Period_No"]);

        $sqlCourse = "SELECT * FROM course WHERE Course_ID = '".$row2["Course_ID"]."'";
        $queryCourse = mysqli_query($conn, $sqlCourse);
        $rowCourse = mysqli_fetch_assoc($queryCourse);
        array_push($courseNames, $rowCourse["Course_Name"]);

        $sqlFaculty = "SELECT * FROM users WHERE USER_ID = '".$row2["Faculty_ID"]."'";
        $queryFaculty = mysqli_query($conn, $sqlFaculty);
        $rowFaculty = mysqli_fetch_assoc($queryFaculty);
        array_push($faculty, $rowFaculty);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Island</title>

    <link rel="stylesheet" href="../plugins/css/index.css">
    <link rel="stylesheet" href="../plugins/css/navbar.css">
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
    </div>

    <div id="main-section">
        <h1 id="banner"> View Schedule </h1>
        <?php
            include 'sidebar.php';
        ?>
        <div class="main-page">

        <br>
        <br>

        <table class="view-table">
            <tr>
                <th>S. No.</th>
                <th>Course</th>
                <th>Faculty</th>
                <th>Room Number </th>
                <th> Building </th>
                <th> Time </th>
                <th> Day </th>
                <th> Semester </th>
                <th> Section </th>
            </tr>
        <?php
            for($i=0; $i<count($sections); $i++){
                if($sections[$i]["Sem_Term_Year"] == "Spring 2021"){
                    echo '<tr>';
                    echo '<td>'.($i+1).'</td>';
                    echo '<td>'.$courseNames[$i].'</td>';
                    echo '<td>'.$faculty[$i]["F_Name"].' '.$faculty[$i]["L_Name"].'</td>';
                    echo '<td>'.$sections[$i]["Room_Num"].'</td>';
                    echo '<td>'.$sections[$i]["Building_Name"].'</td>';


                    $sqlPeriod = "SELECT * FROM period WHERE Period_No = '".$periods[$i]."'";
                    $queryPeriod = mysqli_query($conn, $sqlPeriod);
                    $rowPeriod = mysqli_fetch_assoc($queryPeriod);
                    echo '<td>'.$rowPeriod["Start_Time"].' '.$rowPeriod["End_Time"].'</td>';

                    $timeSlotId = $sections[$i]['Time_Slot_ID'];
                    $sqlDay = "SELECT `Week_Day` FROM `time_slot_day` WHERE `Time_Slot_ID` = '" . $timeSlotId . "'";
                    $queryDay = mysqli_query($conn, $sqlDay);
                    $weekDays = [];
                    while ($dayRow = mysqli_fetch_assoc($queryDay)) {
                        $weekDays[] = $dayRow['Week_Day'];
                    }

                    $strDays = '';
                    if (!empty($weekDays)) {
                        $strDays = implode(',', $weekDays);
                    }

                    echo '<td>' . $strDays . '</td>';
                    echo '<td>'.$sections[$i]["Sem_Term_Year"].'</td>';
                    echo '<td>'.$sections[$i]["Section"].'</td>';
                    echo '</tr>';
                }else{

                }

            }
        ?>
        </table>

        </div>

    </div>
    </div>

    <script src="../plugins/js/nav.js"></script>
</body>

</html>
