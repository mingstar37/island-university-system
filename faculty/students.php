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

$students = array();
for($i=0; $i<count($crns); $i++){
    $crn = $crns[$i];
    $sql = "SELECT * FROM enrollment where CRN_Num = '".$crn."'";
    $query = mysqli_query($conn,$sql);

    while($row2 = mysqli_fetch_assoc($query)){
        array_push($students, $row2);
    }

}

$courseNames = array();
for($i=0; $i<count($CourseIds); $i++){
    $sqlCNames = "SELECT * FROM course WHERE Course_ID = '".$CourseIds[$i]."'";
    $queryCNames = mysqli_query($conn, $sqlCNames);

    while($rowCNames = mysqli_fetch_assoc($queryCNames)){
        array_push($courseNames, $rowCNames["Course_Name"]);
    }
}





if(isset($_POST["edit"])){
    $dId = $_POST['dId'];
    $dl = $_POST['dl'];
    $dbn = $_POST['dbn'];
    $sql3 = "UPDATE enrollment SET Letter_Grade = '".$dbn."' WHERE Student_ID = '".$dId."' AND CRN_Num = '".$dl."'";

    if ($conn->query($sql3) === TRUE) {
        echo 'Successfully updated grades';
        header('location:students.php');
    } else {
      echo "Error: " . $sql3 . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Island - Students</title>

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

        <h2 style="margin-left:50px;"> Student Grades </h2>
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
                    echo '<td><a href="studentGrades.php?crn='.$sections[$i]["CRN_Num"].'&cname='.$result["Course_Name"].'">View</a></td>';
                    echo '</tr>';
                }
            ?>

        </table>

    </div>

    <br>
    <hr>
    <br>
 
</div>

<script src="../plugins/js/nav.js"></script>
</body>
</html>