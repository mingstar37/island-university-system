<?php
session_start();
include '../connection.php';


if(isset($_POST["springSubmit"])){
    $sql = "UPDATE `setting` SET `value` = '" . $_POST["springEnroll"] . "' WHERE `name` = '2021_spring_end'";
    if (!$conn->query($sql)) {
        echo '<script>alert("Error!");</script>';
    }
}

if(isset($_POST["springStartSubmit"])){
    $sql = "UPDATE `setting` SET `value` = '" . $_POST["springEnrollStart"] . "' WHERE `name` = '2021_spring_start'";
    if (!$conn->query($sql)) {
        echo '<script>alert("Error!");</script>';
    }
}

if(isset($_POST["fallSubmit"])){
    $sql = "UPDATE `setting` SET `value` = '" . $_POST["fallEnroll"] . "' WHERE `name` = '2021_fall_start'";
    if (!$conn->query($sql)) {
        echo '<script>alert("Error!");</script>';
    }
}

if(isset($_POST["fallSubmitEnd"])){

    $sql = "UPDATE `setting` SET `value` = '" . $_POST["fallEnrollEnd"] . "' WHERE `name` = '2021_fall_end'";
    if (!$conn->query($sql)) {
        echo '<script>alert("Error!");</script>';
    }
}

if(isset($_POST["gradeSubmit"])){

    $sql = "UPDATE `setting` SET `value` = '" . $_POST["facGrade"] . "' WHERE `name` = '2021_last_grade_date'";
    if (!$conn->query($sql)) {
        echo '<script>alert("Error!");</script>';
    }
}


$sql = "SELECT `value` FROM `setting` WHERE `name` = '2021_spring_start'";
$query = mysqli_query($conn, $sql);
$result = mysqli_fetch_assoc($query);

$springEnrollStart = "";
if (!empty($result)) {
    $springEnrollStart = $result['value'];
}

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

$sql = "SELECT `value` FROM `setting` WHERE `name` = '2021_fall_end'";
$query = mysqli_query($conn, $sql);
$result = mysqli_fetch_assoc($query);

$fallEnrollEnd = "";
if (!empty($result)) {
    $fallEnrollEnd = $result['value'];
}

$sql = "SELECT `value` FROM `setting` WHERE `name` = '2021_last_grade_date'";
$query = mysqli_query($conn, $sql);
$result = mysqli_fetch_assoc($query);

$last_grade_date = "";
if (!empty($result)) {
    $last_grade_date = $result['value'];
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
        <h1 id="banner"> Due Dates </h1>


        <?php
            include 'sidenav.php';
        ?>
        <div class="main-page">
                <br>
                First Day to enroll to a course (Spring Semester): <span style="font-weight:bold;font-size:20px;"><?php echo date('m-d-Y', strtotime( $springEnrollStart )); ?></span>
                <br><br>

                Last Day to enroll to a course (Spring Semester): <span style="font-weight:bold;font-size:20px;"><?php echo date('m-d-Y', strtotime( $springEnrollEnd )); ?></span>
                <br><br>
                Fall Semester Enrollments start from : <span style="font-weight:bold;font-size:20px;"><?php echo date('m-d-Y', strtotime( $fallEnrollStart )); ?></span>
                <br><br>
                Fall Semester Enrollments End on : <span style="font-weight:bold;font-size:20px;"><?php echo date('m-d-Y', strtotime( $fallEnrollEnd )); ?></span>
                <br><br>
                Last Date for Faculty to submit grades (Spring 2021): <span style="font-weight:bold;font-size:20px;"><?php echo date('m-d-Y', strtotime( $last_grade_date )); ?></span>
                <br><br>
                <hr>

                <h3> Update Time Slots </h3>
                <form method="post" action="">
                    Spring 2021 enrollment starting date: <input type="date" name="springEnrollStart" value="<?php echo $springEnrollStart; ?>"/>
                    <input type="submit" name="springStartSubmit" class="btn btn-primary">
                </form>

                <form method="post" action="">
                    Spring 2021 enrollment ending date: <input type="date" name="springEnroll" value="<?php echo $springEnrollEnd; ?>"/>
                    <input type="submit" name="springSubmit" class="btn btn-primary">
                </form>

                <br>

                <form method="post" action="">
                    Fall 2021 enrollment starting date: <input type="date" name="fallEnroll" value="<?php echo $fallEnrollStart; ?>" placeholder="mm-dd-yyyy"/>
                    <input type="submit" name="fallSubmit" class="btn btn-primary">
                </form>

                <form method="post" action="">
                    Fall 2021 enrollment ending date: <input type="date" name="fallEnrollEnd" value="<?php echo $fallEnrollEnd; ?>" placeholder="mm-dd-yyyy"/>
                    <input type="submit" name="fallSubmitEnd" class="btn btn-primary">
                </form>

                <br>
                
                <form method="post" action="">
                    Last Date for Faculty to submit grades (Spring 2021): <input type="date" name="facGrade" value="<?php echo $last_grade_date; ?>" placeholder="mm-dd-yyyy"/>
                    <input type="submit" name="gradeSubmit" class="btn btn-primary">
                </form>
        </div>

    </div>
    </div>

    <script src="../plugins/js/nav.js"></script>
</body>

</html>