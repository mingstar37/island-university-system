<?php
session_start();
include '../connection.php';

    $userId = $_SESSION["uId"];
    $sql = "SELECT * FROM course";
    $query = mysqli_query($conn,$sql);
    


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
                <input type="text" placeholder="Search"/>
                <button>Search</button>
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
        <h1 id="banner"> Island University - Student Schedule </h1>
        <br>

       
        
            <table class="view-table">
                <tr>
                    <th>Course ID</th>
                    <th>Department ID</th>
                    <th>Course Name</th>
                    <th>Course Credits</th>
                    
                </tr>
            <?php
                while($row = mysqli_fetch_assoc($query)){
                    echo '<tr>';
                    echo '<td>'.$row["Course_ID"].'</td>';
                    echo '<td>'.$row["Dept_ID"].'</td>';
                    echo '<td>'.$row["Course_Name"].'</td>';
                    echo '<td>'.$row["Course_Credits"].'</td>';
                    echo '</tr>';
                }
            ?>

            </table>
    </div>

    <script src="../plugins/js/nav.js"></script>
</body>
</html>