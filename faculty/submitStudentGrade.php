<?php
session_start();
include '../connection.php';

$userId = $_SESSION["user_id"]];
$crn = $_GET['crn'];
$cname = $_GET['cname'];

$sqlAtten = "SELECT * FROM enrollment WHERE CRN_Num = '".$crn."' and Date_Enrolled LIKE '%2021%'";
$queryAtten = mysqli_query($conn, $sqlAtten);

$courseStudents = array();

while($rowAtten = mysqli_fetch_assoc($queryAtten)){
    $sqlStudent = "SELECT * FROM users WHERE User_ID = '".$rowAtten["Student_ID"]."'";
    $queryStudent = mysqli_query($conn, $sqlStudent);
    $resultStudent = mysqli_fetch_assoc($queryStudent);

    array_push($courseStudents, $resultStudent);
}

$sqlLastGrade = "SELECT `value` FROM `setting` WHERE `name` = '" . '2021_last_grade_date' ."'";
$squerLastGrade = mysqli_query($conn, $sqlLastGrade);

$lastGradeDate = "";
if ($lastGradeResult = mysqli_fetch_assoc($squerLastGrade)) {
    $lastGradeDate = $lastGradeResult['value'];
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

    </div>

    <div id="main-section">

        <?php
            include 'sidebar.php';
        ?>


        <h2 style="margin-left:50px;"> Submit Grades for '<?php echo $cname;?>' </h2>
        <br>

        Last Date of Grades Submission: <?php echo $lastGradeDate;?>
        <br><br>
        <form method="post" action="">
        <table class="view-table">

                <tr>
                    <th>S. No.</th>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Grades</th>
                </tr>
            <?php
                for($i=0; $i<count($courseStudents); $i++){
                    echo '<tr>';
                    echo '<form method="post" action="">';
                    echo '<td>'.($i+1).'</td>';
                    echo '<td>'.$courseStudents[$i]["User_ID"].'</td>';
                    echo '<td>'.$courseStudents[$i]["F_Name"].' '.$courseStudents[$i]["L_Name"].'</td>';
                    echo '<td>
                        <input type="hidden" name="sid" value="'.$courseStudents[$i]["User_ID"].'">
                    <input type="text" name="grade'.$courseStudents[$i]["User_ID"].'">
                    <input type="submit" name="'.$courseStudents[$i]["User_ID"].'" class="btn btn-primary">
                    </td>';

                    echo '</form>';

                    if(isset($_POST[$courseStudents[$i]["User_ID"]])){
                        $student = $_POST["sid"];
                        $grade = $_POST["grade".$courseStudents[$i]["User_ID"]];

                        $sql = "UPDATE enrollment SET Letter_Grade='".$grade."' WHERE CRN_Num='".$crn."' and Student_ID='".$student."'";
                        if ($conn->query($sql) === TRUE) {
                            echo '<script>alert("Grade submitted")</script>';
                            // header('location: submitStudentGrade.php?crn='.$crn.'&cname='.$cname);
                          } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                          }
                    }
                    echo '</tr>';
                }
            ?>


        </table>
        </form>
    </div>


</div>

<script src="../plugins/js/nav.js"></script>
</body>
</html>
