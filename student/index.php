<?php
session_start();
include '../connection.php';

$_SESSION["credits"] = 0;
$_SESSION["credits2"] = 0;
$userId = $_SESSION["user_id"]];

 //student type

 $sqlType = "SELECT * FROM student WHERE Student_ID='".$userId."' ";
 $queryType = mysqli_query($conn,$sqlType);
 $rowType = mysqli_fetch_assoc($queryType);

 $_SESSION["student_type"] = $rowType["Student_Type"];
 $Student_Type = $_SESSION["student_type"];


 //full_part
 if($Student_Type == 'undergraduate'){
    $sqlPart = "SELECT * FROM undergraduate WHERE Student_ID='".$userId."' ";
 }else{
    $sqlPart = "SELECT * FROM graduate WHERE Student_ID='".$userId."' ";
 }

 $queryPart = mysqli_query($conn,$sqlPart);
 $rowPart = mysqli_fetch_assoc($queryPart);

 if($Student_Type == 'undergraduate'){
     if($rowPart['Undergrad_Type'] == "Fulltime_Undergrad"){
         $_SESSION["full_part"] = 'full';
     }else{
         $_SESSION["full_part"] = 'part';
     }
 }else{
     if($rowPart['Grad_Type'] == "Fulltime_Grad"){
         $_SESSION["full_part"] = 'full';
     }else{
         $_SESSION["full_part"] = 'part';
     }
 }

 if($_SESSION["full_part"] == 'full'){
    $_SESSION["required_creds"] = 16;
    $_SESSION["required_creds2"] = 16;
 }else{
    $_SESSION["required_creds"] = 12;
    $_SESSION["required_creds2"] = 12;
 }


$sql = "SELECT * FROM enrollment WHERE Student_ID='".$userId."' and Date_Enrolled LIKE '%2021%' ";
$query = mysqli_query($conn,$sql);

$num_of_courses = 0;
if ($query)
    {
        $rows = mysqli_num_rows($query);
        // $num_of_courses = $rows;

        while($row = mysqli_fetch_assoc($query)){
            $sqlSec = "SELECT * FROM section WHERE CRN_Num = '".$row["CRN_Num"]."'";
            $querySec = mysqli_query($conn,$sqlSec);
            $rowSec = mysqli_fetch_assoc($querySec);

            $crn = $rowSec["Course_ID"];
            $sql2 = "SELECT * FROM course WHERE Course_ID = '".$crn."'";
            $query2 = mysqli_query($conn,$sql2);
            $row2 = mysqli_fetch_assoc($query2);

            if($rowSec["Sem_Term_Year"] == "Spring 2021"){
                $_SESSION["credits"] = $_SESSION["credits"] + $row2["Course_Credits"];
                $num_of_courses ++;
            }else{
                $_SESSION["credits2"] = $_SESSION["credits2"] + $row2["Course_Credits"];
            }

        }
        $_SESSION["required_creds"] = $_SESSION["required_creds"] - $_SESSION["credits"];
        $_SESSION["required_creds2"] = $_SESSION["required_creds2"] - $_SESSION["credits2"];
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
        <h1 id="banner"> Island University - Student </h1>
        <?php
            include 'sidebar.php';
        ?>
        <!-- <div id="calender-section">
            <div class="calender-heading">Academic Calender</div>
            <div class="calender-body">
                <div class="calender-section">
                    <span class="calender-term">Spring</span>
                    <table class="calender-table">
                        <tr>
                            <th>Date</th>
                            <th>Academic Event</th>
                        </tr>
                        <tr>
                            <td>Jan 27 (All day) to <br>Feb 3 2021 (All day)</td>
                            <td>Add/Drop (no fee) Late Registration <br>($50 fee) on www.islanduniversity.edu</td>
                        </tr>
                        <tr>
                            <td>Feb 8 2021 </td>
                            <td>In-Person Course Sessions Begin</td>
                        </tr>
                        <tr>
                            <td>Feb 15 2021 </td>
                            <td>Presidents Day – no classes</td>
                        </tr>
                    </table>
                </div>
                <div class="calender-section">
                <span class="calender-term">Fall</span>
                    <table class="calender-table">
                        <tr>
                            <th>Date</th>
                            <th>Academic Event</th>
                        </tr>
                        <tr>
                            <td>Sept 3 2021 (All day)</td>
                            <td>Add/Drop (no fee) Late Registration ($50 fee) on<br> www.islanduniversity.edu</td>
                        </tr>
                        <tr>
                            <td>Sept 6 2021 (All day) </td>
                            <td>Labor Day – Classes Canceled</td>
                        </tr>
                        <tr>
                            <td>Sept 7 2021</td>
                            <td>In-Person Course Sessions Begin</td>
                        </tr>
                    </table>
                </div>
            </div>

        </div> -->
        <div id="login-section">
            Welcome Student
            <br>
            <br>
            Number of courses enrolled: <?php echo $num_of_courses ?>
            <br>
            Credits Fetched :
            <?php
            echo $_SESSION["credits"] ;
            ?>
            <br>
            You are: <?php echo $_SESSION["full_part"]." ".$Student_Type ?>
            <br>
             <!-- Credits Remaining: -->
              <?php
            //   echo $_SESSION["required_creds"];
              ?>
        </div>
    </div>

    <script src="../plugins/js/nav.js"></script>
</body>
</html>
