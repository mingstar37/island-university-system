<?php
include '../connection.php';
session_start();


if(isset($_POST["submitPrerequisite"])){
    $courseId = $_POST["courseId"];

    $sqlPre = "UPDATE course SET Prereq_Course_ID = '' WHERE Course_ID = '".$courseId."'";
    if($conn->query($sqlPre) === TRUE){
        $sql = "DELETE FROM `prerequisite` WHERE `Course_ID` = " . $courseId;

        if ($conn->query($sql) == true ) {
            echo "<script>alert('Prerequisite Removed Successfully')</script>";
        } else {
            echo "Error".$sql."<br>".$conn->error;
        }
    }else{
        echo "Error".$sql."<br>".$conn->error;
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Island</title>

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


        <?php
        include 'sidenav.php';
        ?>

        <div class="main-page">
            <h3 id="add-student-section"> Remove Prerequisite </h3>
            <br>
            <form method="post" action="">
                Enter Course ID: <input type="text" name="courseId" placeholder="Course ID" /> <br><br>
                <input type="submit" class="btn btn-primary" name="submitPrerequisite" value="Remove Prerequisite" />
                <form>

                    <br>
                    <hr>


        </div>
    </div>


    </div>
    </div>



    <script src="../plugins/js/nav.js"></script>

</body>

</html>