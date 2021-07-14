<?php
session_start();
include '../connection.php';

$userId = $_SESSION["user_id"]];

$sqlSection = "Select * from section where Faculty_ID = '".$userId."'";
$querySection = mysqli_query($conn,$sqlSection);

$CourseIds = array();
$crns = array();
while($row = mysqli_fetch_assoc($querySection)){
    array_push($CourseIds, $row["Course_ID"]);
    array_push($crns, $row["CRN_Num"]);
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





// if(isset($_POST["submit"])){
//     $dId = $_POST['dId'];
//     $dn = $_POST['dn'];
//     $df = $_POST['df'];
//     $dl = $_POST['dl'];
//     $dbn = $_POST['dbn'];
//     $sql2 = "INSERT INTO course VALUES (".$dId.",'".$dn."','".$df."','".$dl."','".$dbn."')";

//     if ($conn->query($sql2) === TRUE) {
//         echo 'Successfully added course';
//         header('location:courses.php');
//     } else {
//       echo "Error: " . $sql2 . "<br>" . $conn->error;
//     }
// }

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

        <h2 style="margin-left:50px;"> Update Student Grades </h2>
        <br>
        <!-- <table class="view-table">
                <tr>
                    <th>Student ID</th>
                    <th>CRN Number</th>
                    <th>Date Enrolled</th>
                    <th>Letter Grade</th>
                </tr>
            <?php
                for($j=0; $j<count($students); $j++){
                    echo '<tr>';
                    echo '<td>'.$students[$j]["Student_ID"].'</td>';
                    echo '<td>'.$students[$j]["CRN_Num"].'</td>';
                    echo '<td>'.$students[$j]["Date_Enrolled"].'</td>';
                    echo '<td>'.$students[$j]["Letter_Grade"].'</td>';
                    echo '</tr>';
                }
            ?>

        </table> -->
    </div>

    <br>
    <hr>
    <br>
    <div>

        <form method="post" action="">
                Student Id: <input type="text" name="dId" placeholder="Student Id" /> <br><br>
                CRN Number: <input type="text" name="dl" placeholder="CRN Number" /> <br><br>
                Letter Grades: <input type="text" name="dbn" placeholder="Grade" /> <br><br>
                <input type="submit" class="btn btn-primary" name="edit" value="Update Grade" />
        <form>
    </div>
</div>

<script src="../plugins/js/nav.js"></script>
</body>
</html>
