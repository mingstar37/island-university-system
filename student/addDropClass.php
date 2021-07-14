<?php
session_start();
include '../connection.php';


$crnsEnrolled = array();
$coursesEnrolled = array();
$crnsEnrolled2 = array();
$coursesEnrolled2 = array();
$term = '';


$sql = "SELECT `value` FROM `setting` WHERE `name` = '2021_spring_start'";
$query = mysqli_query($conn, $sql);
$result = mysqli_fetch_assoc($query);

$springEnrollStart = "";
if (!empty($result)) {
    $springEnrollStart = $result['value'];
}

$sql = "SELECT `value` FROM `setting` WHERE `name` = '2021_spring_end'";
$query = mysqli_query($conn, $sql);
$result = mysqli_fetch_assoc($query);

$springEnrollEnd = "";
if (!empty($result)) {
    $springEnrollEnd = $result['value'];
}

$sql = "SELECT `value` FROM `setting` WHERE `name` = '2021_fall_start'";
$query = mysqli_query($conn, $sql);
$result = mysqli_fetch_assoc($query);

$fallEnrollStart = "";
if (!empty($result)) {
    $fallEnrollStart = $result['value'];
}

$sql = "SELECT `value` FROM `setting` WHERE `name` = '2021_fall_end'";
$query = mysqli_query($conn, $sql);
$result = mysqli_fetch_assoc($query);

$fallEnrollEnd = "";
if (!empty($result)) {
    $fallEnrollEnd = $result['value'];
}

if(isset($_POST["termSubmit"])){
    $term = $_POST["termSubmit"];
    $term = $_POST["term"];

    $curDate = date('Y-m-d');

    $error = '';
    $bEnable = true;
    if($term == "Spring 2021"){
        if ($curDate < $springEnrollStart || $curDate > $springEnrollEnd) {
            echo '<script>alert("You cannot access!")</script>';
            $bEnable = false;
        }
    } else {
        if ($curDate < $fallEnrollStart || $curDate > $fallEnrollEnd) {
            echo '<script>alert("You cannot access!")</script>';
            $bEnable = false;
        }
    }

    if ($bEnable == true) {
        $sql = "SELECT `value` FROM `setting` WHERE `name` = '2021_fall_start'";
        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_assoc($query);

        $fallEnrollStart = "";
        if (!empty($result)) {
            $fallEnrollStart = $result['value'];
        }

        $sql = "SELECT `value` FROM `setting` WHERE `name` = '2021_fall_end'";
        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_assoc($query);

        $fallEnrollEnd = "";
        if (!empty($result)) {
            $fallEnrollEnd = $result['value'];
        }

        // check

        $sql = "SELECT * FROM section WHERE Sem_Term_Year = '".$term."'";
        $query = mysqli_query($conn,$sql);


        $userId = $_SESSION["user_id"]];
        $sql3 = "SELECT * FROM enrollment WHERE Student_ID='".$userId."' and Date_Enrolled LIKE '%2021%'";
        $query3 = mysqli_query($conn,$sql3);


        $enrolled = array();
        $enrolled2 = array();
        $month = 0;
        while($row = mysqli_fetch_assoc($query3)){
            // array_push($enrolled, $row);

            $month = date('m' ,strtotime($row["Date_Enrolled"]));

            $sqlEnrolled2 = "SELECT * FROM section WHERE CRN_Num = '".$row['CRN_Num']."'";
            $queryEnrolled2 = mysqli_query($conn, $sqlEnrolled2);
            $rowEnrolled2 = mysqli_fetch_assoc($queryEnrolled2);

            if($rowEnrolled2['Sem_Term_Year'] == 'Spring 2021'){
                array_push($crnsEnrolled, $rowEnrolled2["Course_ID"]);
                array_push($enrolled, $row);
            }else{
                array_push($crnsEnrolled2, $rowEnrolled2["Course_ID"]);
                array_push($enrolled2, $row);
            }

        }

        for($i=0; $i< count($crnsEnrolled); $i++){
            $sqlEnrolled3 = "SELECT * FROM course WHERE Course_ID = '".$crnsEnrolled[$i]."'";
            $queryEnrolled3 = mysqli_query($conn, $sqlEnrolled3);
            $rowEnrolled3 = mysqli_fetch_assoc($queryEnrolled3);
            array_push($coursesEnrolled, $rowEnrolled3["Course_Name"]);
        }

        for($i=0; $i< count($crnsEnrolled2); $i++){
            $sqlEnrolled3 = "SELECT * FROM course WHERE Course_ID = '".$crnsEnrolled2[$i]."'";
            $queryEnrolled3 = mysqli_query($conn, $sqlEnrolled3);
            $rowEnrolled3 = mysqli_fetch_assoc($queryEnrolled3);
            array_push($coursesEnrolled2, $rowEnrolled3["Course_Name"]);
        }

        $courses = array();
        $sections = array();
        $prereqs = array();
        $periods = array();
        while($row = mysqli_fetch_assoc($query)){
            $crn = $row["Course_ID"];
            $sql2 = "SELECT * FROM course WHERE Course_ID = '".$crn."'";
            $query2 = mysqli_query($conn,$sql2);
            $row2 = mysqli_fetch_assoc($query2);
            array_push($courses, $row2);
            array_push($prereqs, $row2["Prereq_Course_ID"]);

            // $sqlSection = "SELECT * FROM section WHERE Course_ID = '".$crn."'";
            // $querySection = mysqli_query($conn, $sqlSection);
            // $rowSection = mysqli_fetch_assoc($querySection);
            array_push($sections, $row);

            $sqlTimes = "SELECT * FROM time_slot_period WHERE Time_Slot_ID = '".$row["Time_Slot_ID"]."'";
            $querytimes = mysqli_query($conn, $sqlTimes);
            $rowTimes = mysqli_fetch_assoc($querytimes);
            array_push($periods, $rowTimes["Period_No"]);


        }

        $available = array();
        for($i=0; $i<count($prereqs); $i++){
            $sqlAllowed = "SELECT * FROM enrollment WHERE Student_ID = '".$_SESSION["user_id"]]."' and CRN_Num = '".getCrn($prereqs[$i] , $conn)."'";
            $queryAllowed = mysqli_query($conn, $sqlAllowed);
            $rowAllowed = mysqli_num_rows($queryAllowed);

            if($rowAllowed == 0){
                array_push($available, 0);
            }else{
                array_push($available, 1);
            }
        }


        foreach($courses as $course){
            for($i=0; $i<count($enrolled); $i++){
                if($course["Course_ID"] == $enrolled[$i]["CRN_Num"]){
                    if (($key = array_search($course, $courses)) !== false) {
                        array_splice($courses, $key, 1);
                    }
                }
            }
        }

        foreach($courses as $course){
            for($i=0; $i<count($enrolled2); $i++){
                if($course["Course_ID"] == $enrolled2[$i]["CRN_Num"]){
                    if (($key = array_search($course, $courses)) !== false) {
                        array_splice($courses, $key, 1);
                    }
                }
            }
        }
    }
}

function getCrn($courseid, $conn) {
    $sqlcrn = "SELECT * FROM section WHERE Course_ID = '".$courseid."'";
    $querycrn = mysqli_query($conn, $sqlcrn);
    $rowcrn = mysqli_fetch_assoc($querycrn);

    return $rowcrn["CRN_Num"];

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Island University - Student</title>

    <link rel="stylesheet" href="../plugins/css/index.css">
    <link rel="stylesheet" href="../plugins/css/navbar.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>
    <div id="header">
        <div id="upper-header">
            <div class="search-box">
                <!-- <input type="text" placeholder="Search"/> -->
                <!-- <button>Search</button> -->
                <?php
                       echo $_SESSION["uemail"];
                ?>
                <a href="../logout.php"><button>Logout</button></a>
            </div>
        </div>
        <!-- <div id="lower-header">
            <a href="index.php"><div class="navi2"> Home </div></a>
            <a href="holds.php"><div class="navi2"> View Holds </div></a>
            <a href="addDropClass.php"><div class="navi2"> Add Drop Class </div></a>
            <a href="schedule.php"><div class="navi2"> Schedule </div></a>
            <a href="history.php"><div class="navi2"> History </div></a>
        </div> -->
    </div>


    <div id="main-section">
    <?php
        include 'sidebar.php';
    ?>
        <h1 id="banner"> Island University - Student Classes </h1>

           <div class="form-div">
                <form method="post" action="">
                    <select name="term">
                        <option value="Spring 2021"> Spring </option>
                        <option value="Fall 2021"> Fall </option>
                    </select>

                    <input type="submit" name="termSubmit" value="submit" />
                </form>
           </div>
           <br>
           <hr>
           <br>

        <div class="add-class-div">
        <?php
         if(isset($_POST["termSubmit"]) && $bEnable == true){
                echo '<b>Selected Term : '.$term.'</b>';
                echo '<div class="enrolled-div">';
                echo '<h2 style="padding-left:50px;">Enrolled Courses </h2>';
                if($term == "Spring 2021"){
                    echo '<p> <b>Last date to enroll: '.$springEnrollEnd.'</b></p>';
                }else{
                    echo '<p> <b>Last date to enroll: '.$fallEnrollEnd.'</b></p>';
                }

                echo '<table class="view-table">';
                echo '<tr>';
                echo '<th>S. No.</th>';
                echo '<th>Course</th>';
                echo '<th>Date Enrolled</th>';
                echo '<th>Letter Grade</th>';
                echo '<th>Drop</th>';
                echo '</tr>';
                if($term == 'Spring 2021'){
                    for ($i=0; $i < count($enrolled); $i++) {
                        echo '<tr>';
                        echo '<td>'.($i+1).'</td>';
                        echo '<td>'.$coursesEnrolled[$i].'</td>';
                        echo '<td>'.$enrolled[$i]["Date_Enrolled"].'</td>';
                        echo '<td>Pending</td>';
                        $number = $enrolled[$i]["CRN_Num"];
                        echo '<td><a href="dropCourseFromStudent.php?courseID='.$number.'">Drop</td>';
                        echo '</tr>';
                    }
                }else{
                    for ($i=0; $i < count($enrolled2); $i++) {
                        echo '<tr>';
                        echo '<td>'.($i+1).'</td>';
                        echo '<td>'.$coursesEnrolled2[$i].'</td>';
                        echo '<td>'.$enrolled2[$i]["Date_Enrolled"].'</td>';
                        echo '<td>Pending</td>';
                        $number = $enrolled2[$i]["CRN_Num"];
                        echo '<td><a href="dropCourseFromStudent.php?courseID='.$number.'">Drop</td>';
                        echo '</tr>';
                    }
                }

                echo '</table>';


                echo '</div>';


                echo '<div class="available_courses" style="margin-top:100px;">';
                echo '<h2 style="padding-left:50px;">Available Courses </h2>';
                echo '<table class="view-table">';
                echo '<tr>';
                echo '<th>Course ID</th>';
                echo '<th>Prerequisite Course ID</th>';
                echo '<th>Department ID</th>';
                echo '<th>Course Name</th>';
                echo '<th>Room Number</th>';
                echo '<th>Building Name</th>';
                echo '<th>Faculty</th>';
                echo '<th>Days</th>';
                echo '<th>Time</th>';
                echo '<th>Course Credits</th>';
                echo '<th>Action</th>';
                echo '</tr>';
                for ($i=0; $i < count($courses); $i++) {
                    if (empty($courses[$i]["Course_ID"])) {
                        continue;
                    }
                    $realReq = !empty($courses[$i]["Prereq_Course_ID"]) ? $courses[$i]["Prereq_Course_ID"] : '';
                    echo '<tr>';
                    echo '<td>'.$courses[$i]["Course_ID"].'</td>';
                    echo '<td>'. $realReq .'</td>';
                    echo '<td>'.$courses[$i]["Dept_ID"].'</td>';
                    echo '<td>'.$courses[$i]["Course_Name"].'</td>';
                    echo '<td>'.$sections[$i]["Room_Num"].'</td>';
                    echo '<td>'.$sections[$i]["Building_Name"].'</td>';

                    $sqlFaculty = "SELECT * FROM users WHERE User_ID='".$sections[$i]["Faculty_ID"]."'";
                    $queryFaculty = mysqli_query($conn, $sqlFaculty);
                    $rowFaculty = mysqli_fetch_assoc($queryFaculty);

                    echo '<td>'.$rowFaculty["F_Name"].' '.$rowFaculty["L_Name"].'</td>';


                    $days = array();
                    $sqlDays = "SELECT * FROM time_slot_day where Time_Slot_ID = '".$sections[$i]["Time_Slot_ID"]."'";
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


                    echo '<td>'.$courses[$i]["Course_Credits"].'</td>';
                    if($term == "Spring 2021"){
//                        new logic
                        if (!empty($courses[$i]["Prereq_Course_ID"]) && $available[$i] == 0 ) {
                            echo '<td>Cannot Add</td>';
                        } else {
                            if ($courses[$i]["Course_Credits"] <= $_SESSION["required_creds"] && (date("Y-m-d") < $springEnrollEnd)) {
                                echo '<td><a href="addCourseToStudent.php?courseID='.getcrn($courses[$i]["Course_ID"], $conn).'&credits='.$courses[$i]["Course_Credits"].'&term=Spring 2021">Add Class</a></td>';

                            } else {
                                echo '<td>Cannot Add</td>';
                            }
                        }

                        /////////////
//                        if( empty($courses[$i]["Prereq_Course_ID"] && $courses[$i]["Course_Credits"] <= $_SESSION["required_creds"] && (date("Y-m-d") < $springEnrollEnd) && $available[$i] == 0)){
//                            // echo $available[$i];
//                            echo '<td><a href="addCourseToStudent.php?courseID='.getcrn($courses[$i]["Course_ID"], $conn).'&credits='.$courses[$i]["Course_Credits"].'&term=Spring 2021">Add Class</a></td>';
//                        }else{
//                            echo '<td>Cannot Add</td>';
//                        }
                    }else{

                        // new logic
                        if (!empty($course[$i]["Prereq_Course_ID"]) && $available[$i] == 0 ) {
                            echo '<td>Cannot Add</td>';
                        } else {
                            if ($courses[$i]["Course_Credits"] <= $_SESSION["required_creds2"] && (date("Y-m-d") < $fallEnrollEnd)) {
                                echo '<td><a href="addCourseToStudent.php?courseID='.getcrn($courses[$i]["Course_ID"], $conn).'&credits='.$courses[$i]["Course_Credits"].'&term=Spring 2021">Add Class</a></td>';
                            } else {
                                echo '<td>Cannot Add</td>';
                            }
                        }

                        /////////////////
                        ///
//                        if( $courses[$i]["Course_Credits"] <= $_SESSION["required_creds2"] && (date("Y-m-d") < $fallEnrollEnd) && $available[$i] == 1){
//                            // echo $available[$i];
//                            echo '<td><a href="addCourseToStudent.php?courseID='.getcrn($courses[$i]["Course_ID"], $conn).'&credits='.$courses[$i]["Course_Credits"].'&term=Fall 2021">Add Class</a></td>';
//                        }else{
//                            echo '<td>Cannot Add</td>';
//                        }
                    }
                    echo '</tr>';
                }
                echo '</div>';

            }

        ?>
        </div>



    </div>

    <script src="../plugins/js/nav.js"></script>
</body>
</html>
