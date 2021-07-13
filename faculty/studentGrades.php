<?php
session_start();
include '../connection.php';

$userId = $_SESSION["uId"];
$crn = $_GET['crn'];
$cname = $_GET['cname'];

$sqlAtten = "SELECT * FROM enrollment WHERE CRN_Num = '".$crn."'";
$queryAtten = mysqli_query($conn, $sqlAtten);

$courseStudents = array();

while($rowAtten = mysqli_fetch_assoc($queryAtten)){
    $sqlStudent = "SELECT * FROM users WHERE User_ID = '".$rowAtten["Student_ID"]."'";
    $queryStudent = mysqli_query($conn, $sqlStudent);
    $resultStudent = mysqli_fetch_assoc($queryStudent);

    array_push($courseStudents, $resultStudent);
}

$attendance = array();
$sql = "SELECT * FROM attendance where CRN_Num = '".$crn."' ORDER BY Date_Attended DESC";
$query = mysqli_query($conn,$sql);

while($row2 = mysqli_fetch_assoc($query)){
    array_push($attendance, $row2);
}



$students = array();
$sqlEnroll = "SELECT * FROM enrollment where CRN_Num = '".$crn."' and Date_Enrolled LIKE '%2021%' ";
$queryEnroll = mysqli_query($conn,$sqlEnroll);

while($row3 = mysqli_fetch_assoc($queryEnroll)){
    array_push($students, $row3);
}

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
    <title>Island - Faculty</title>

    <link rel="stylesheet" href="../plugins/css/index.css">
    <link rel="stylesheet" href="../plugins/css/navbar.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>

<body>
    <div id="header">
        <div id="upper-header">

            <div class="search-box">

                <a href="../logout.php"><button>Logout</button></a>
            </div>
        </div>

    </div>

    <div id="main-section">

        <?php
        include 'sidebar.php';
        ?>

        <h2 style="margin-left:50px;"> Attendance of '<?php echo $cname ?>' </h2>
        <br>
        <table class="view-table">
            <tr>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Course Name</th>
                <th>Date Enrolled</th>
                <th>Letter Grade</th>
            </tr>
            <?php
            for ($j = 0; $j < count($students); $j++) {
                echo '<tr>';
                echo '<td>' . $students[$j]["Student_ID"] . '</td>';

                $sqlSName = "SELECT * FROM users WHERE User_ID = '" . $students[$j]["Student_ID"] . "'";
                $querySName = mysqli_query($conn, $sqlSName);
                $rowSName = mysqli_fetch_assoc($querySName);
                echo '<td>' . $rowSName["F_Name"] . ' ' . $rowSName["L_Name"] . '</td>';    


                echo '<td>' . $cname . '</td>';
                echo '<td>' . $students[$j]["Date_Enrolled"] . '</td>';
                echo '<td>' . $students[$j]["Letter_Grade"] . '</td>';
                echo '</tr>';
            }
            ?>

        </table>

    </div>
</body>

</html>