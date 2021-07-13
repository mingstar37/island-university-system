<?php
session_start();
include '../connection.php';

$sql = "SELECT `value` FROM `setting` WHERE `name` = '2021_spring_end'";
$query = mysqli_query($conn, $sql);
$result = mysqli_fetch_assoc($query);

$springEnrollEnd = "";
if (!empty($result)) {
    $springEnrollEnd = $result['value'];
}

$sql = "SELECT `value` FROM `setting` WHERE `name` = '2021_fall_start'";
$query = mysqli_query($conn, $sql);
$result = mysqli_fetch_assoc($query);

$fallEnrollStart = "";
if (!empty($result)) {
    $fallEnrollStart = $result['value'];
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Island - Admin</title>

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
        <h1 id="banner"> Due Dates </h1>

        <?php 
            include 'sidebar.php';
        ?>
        <div class="main-page">
                <br>
                Last Day to enroll to a course (Spring Semester): <span style="font-weight:bold;font-size:20px;"><?php echo $springEnrollEnd; ?></span>
                <br><br>
                Fall Semester Enrollments start from : <span style="font-weight:bold;font-size:20px;"><?php echo $fallEnrollStart; ?></span>

        </div>

    </div>
    </div>

    <script src="../plugins/js/nav.js"></script>
</body>

</html>