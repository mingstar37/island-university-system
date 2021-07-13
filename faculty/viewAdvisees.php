<?php
session_start();
include '../connection.php';

$userId = $_SESSION["uId"];
$sql = "SELECT * FROM advisor WHERE Faculty_ID = '".$userId."'";
$query = mysqli_query($conn,$sql);

$advisors = array();
$faculties = array();
while ($row = mysqli_fetch_assoc($query)){
    $advisorId = $row["Student_ID"];
    array_push($advisors, $row);

    $sql2 = "SELECT * FROM users WHERE User_ID = '".$advisorId."'";
    $query2 = mysqli_query($conn,$sql2);

    $rowFaculty = mysqli_fetch_assoc($query2);
    array_push($faculties, $rowFaculty);
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Island - Student</title>

    <link rel="stylesheet" href="../plugins/css/index.css">
    <link rel="stylesheet" href="../plugins/css/navbar.css">
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
    </div>

    <div id="main-section">
        <h1 id="banner"> View Advisees </h1>

        <?php 
            include 'sidebar.php';
        ?>
        <div class="main-page">
                <?php
                    for($i=0; $i<count($advisors); $i++){
                        ?>
                        
                        Advisee Name: <span style="font-weight:bold;font-size:20px;"><?php echo $faculties[$i]["F_Name"].' '.$faculties[$i]["L_Name"] ?></span> 
                        <br>
                        Time of Advisement: <span style="font-weight:bold;font-size:20px;"><?php echo $advisors[$i]["Time_Of_Advisement"]?></span> 
                        <br><br>
                        <?php
                    }

                ?>
            

        </div>

    </div>
    </div>

    <script src="../plugins/js/nav.js"></script>
</body>

</html>