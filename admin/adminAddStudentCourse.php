<?php
include '../connection.php';
session_start();

$sql = "SELECT `value` FROM `setting` WHERE `name` = '2021_fall_end'";
$query = mysqli_query($conn, $sql);
$result = mysqli_fetch_assoc($query);

$fallEnrollEnd = "";
if (!empty($result)) {
    $fallEnrollEnd = $result['value'];
}

$sql = "SELECT `value` FROM `setting` WHERE `name` = '2021_spring_end'";
$query = mysqli_query($conn, $sql);
$result = mysqli_fetch_assoc($query);

$springEnrollEnd = "";
if (!empty($result)) {
    $springEnrollEnd = $result['value'];
}


$student_fetched = false;
if(isset($_POST["submit"])){
    $sId = $_POST["studentId"];
    $_SESSION['sId'] = $sId;


    $sqlStudent = "SELECT * FROM enrollment WHERE Student_ID='".$sId."'";
    $queryStudent = mysqli_query($conn,$sqlStudent);


    $student_fetched = true;
}



if(isset($_POST["termSubmit"])){
    $term = $_POST["term"];
    $sql = "SELECT * FROM section WHERE Sem_Term_Year = '".$term."'";
    $query = mysqli_query($conn,$sql);


    $userId = $_SESSION["sId"];
    $sql3 = "SELECT * FROM enrollment WHERE Student_ID='".$userId."' ";
    $query3 = mysqli_query($conn,$sql3);
    $enrolled = array();
    while($row = mysqli_fetch_assoc($query3)){
        array_push($enrolled, $row);
    }

    $courses = array();
    while($row = mysqli_fetch_assoc($query)){
        $crn = $row["Course_ID"];
        $sql2 = "SELECT * FROM course WHERE Course_ID = '".$crn."'";
        $query2 = mysqli_query($conn,$sql2);
        $row2 = mysqli_fetch_assoc($query2);
        array_push($courses, $row2);



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

   
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Island - Admin View Course</title>

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

        <?php
            include 'sidenav.php';
        ?>
        <div class="main-page">


            <h3 id="add-student-section"> Add Student Courses </h3>
            <br>
           <form method="post" action="">
                Enter Student ID: <input type="text" name="studentId" placeholder="Student Id" /> <br><br>
                <input type="submit" class="btn btn-primary" name="submit" value="Add Student Course" />
        <form>

        <br>
        <hr>
        <br>
        <?php 
            if($student_fetched == true){

           
        ?>
        <form method="post" action="">
                    <select name="term">
                        <option value="Spring 2021"> Spring </option>
                        <option value="Fall 2021"> Fall </option>
                    </select>

                    <input type="submit" name="termSubmit" value="submit" />
                </form>

                <br>
                <hr>
                <br>

                <?php
         }
        
        ?>
        
        <?php

         if(isset($_POST["termSubmit"])){
                echo '<div class="enrolled-div">';
                echo '<h2 style="padding-left:50px;">Enrolled Courses </h2>';
                echo '<p> <b>Last date to enroll: '.$springEnrollEnd.'</b></p>';
                echo '<table class="view-table">';
                echo '<tr>';
                echo '<th>S. No.</th>';
                echo '<th>CRN Number</th>';
                echo '<th>Date Enrolled</th>';
                echo '<th>Letter Grade</th>';
                echo '<th>Drop</th>';
                echo '</tr>';
        
                for ($i=0; $i < count($enrolled); $i++) { 
                    echo '<tr>';
                    echo '<td>'.($i+1).'</td>';
                    echo '<td>'.$enrolled[$i]["CRN_Num"].'</td>';
                    echo '<td>'.$enrolled[$i]["Date_Enrolled"].'</td>';
                    echo '<td>'.$enrolled[$i]["Letter_Grade"].'</td>';
                    $number = $enrolled[$i]["CRN_Num"];
                    echo '<td><a href="dropCourseFromStudent.php?studentID='. $_SESSION["sId"].'&courseID='.$number.'">Drop</td>';
                    echo '</tr>';
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
                echo '<th>Course Credits</th>';
                echo '<th>Action</th>';
                echo '</tr>';
                for ($i=0; $i < count($courses); $i++) { 
                    echo '<tr>';
                    echo '<td>'.$courses[$i]["Course_ID"].'</td>';
                    echo '<td>'.$courses[$i]["Prereq_Course_ID"].'</td>';
                    echo '<td>'.$courses[$i]["Dept_ID"].'</td>';
                    echo '<td>'.$courses[$i]["Course_Name"].'</td>';
                    echo '<td>'.$courses[$i]["Course_Credits"].'</td>';
                    if(date("Y-m-d") > $springEnrollEnd){
                        echo 'Cannot';
                    }else{
                        echo '<td><a href="addCourseToStudent.php?studentID='. $_SESSION["sId"].'&courseID='.$courses[$i]["Course_ID"].'&credits='.$courses[$i]["Course_Credits"].'">Add Class</a></td>';
                    }
                    
                    
                    echo '</tr>';
                }
                echo '</div>';

            }
           
        ?>

     
               
        </div>
    </div>


    </div>
    </div>

    <script src="../plugins/js/nav.js"></script>

</body>

</html>