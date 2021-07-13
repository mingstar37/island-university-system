<?php
session_start();
include '../connection.php';

$_SESSION["credits"] = 0;
$userId = $_SESSION["uId"];

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
    <h1 id="banner"> Island University - Faculty </h1>

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

        <?php
            include 'sidebar.php';
        ?>


<div id="login-section">
    Welcome Faculty
   
</div>
    </div>
</div>


<script src="../plugins/js/nav.js"></script>
</body>
</html>