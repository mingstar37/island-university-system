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
                    <!-- <th>Course CRN Number</th> -->
                    <th> S. No. </th>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Date Attended</th>
                    <th>Present</th>
                </tr>
            <?php
                for($j=0; $j<count($attendance); $j++){
                    echo '<tr>';

                    // $sqlSection = "SELECT * FROM section WHERE CRN_Num = '".$attendance[$j]["CRN_Num"]."'";
                    // $querySection = mysqli_query($conn, $sqlSection);
                    // $rowSection = mysqli_fetch_assoc($querySection);

                    // $sqlCourse = "SELECT * FROM course WHERE Course_ID = '".$rowSection["Course_ID"]."'";
                    // $queryCourse = mysqli_query($conn, $sqlCourse);
                    // $rowCourse = mysqli_fetch_assoc($queryCourse);

                    // echo '<td>'.$attendance[$j]["CRN_Num"].'</td>';
                    echo '<td>'.($j+1).'</td>';

                    $sqlStudent = "SELECT * FROM users WHERE User_ID = '".$attendance[$j]["Student_ID"]."'";
                    $queryStudent = mysqli_query($conn, $sqlStudent);
                    $rowStudent = mysqli_fetch_assoc($queryStudent);

                    echo '<td>'.$attendance[$j]["Student_ID"].'</td>';
                    echo '<td>'.$rowStudent["F_Name"].' '.$rowStudent["L_Name"].'</td>';
                    echo '<td>'.$attendance[$j]["Date_Attended"].'</td>';
                    echo '<td>'.$attendance[$j]["Present"].'</td>';
                    echo '</tr>';
                }
            ?>

         </table>

         </div>
</body>
</html>