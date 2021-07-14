<?php
session_start();
include '../connection.php';

$userId = $_SESSION["user_id"]];

$sql = "SELECT * FROM course ORDER BY `Course_ID` DESC";
$query = mysqli_query($conn, $sql);

if (isset($_POST["submit"])) {
    $dId = $_POST['dId'];
    $dn = $_POST['dn'];
    $df = $_POST['df'];
    $dl = $_POST['dl'];
    $dbn = $_POST['dbn'];
    $sql2 = "INSERT INTO course (`Course_ID`, `Prereq_Course_ID`, `Dept_ID`, `Course_Name`, `Course_Credits`) VALUES (" . $dId . ",'" . $dn . "','" . $df . "','" . $dl . "','" . $dbn . "')";

    if ($conn->query($sql2) === TRUE) {
        echo 'Successfully added course';
        header('location:courses.php');
    } else {
        echo "Error: " . $sql2 . "<br>" . $conn->error;
    }
}

if (isset($_POST["edit"])) {
    $dId = $_POST['dId'];
    $dn = $_POST['dn'];
    $df = $_POST['df'];
    $dl = $_POST['dl'];
    $dbn = $_POST['dbn'];
    $sql3 = "UPDATE course SET Prereq_Course_ID = '" . $dn . "', Dept_ID = '" . $df . "', Course_Name = '" . $dl . "', Course_Credits = '" . $dbn . "' WHERE Course_ID = '" . $dId . "'";

    if ($conn->query($sql3) === TRUE) {
        echo 'Successfully updated course';
    } else {
        echo "Error: " . $sql3 . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Island - Courses</title>

    <link rel="stylesheet" href="../plugins/css/index.css">
    <link rel="stylesheet" href="../plugins/css/navbar.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
</head>

<body>
    <div id="header">
        <div id="upper-header">
            <!-- <div class="user-name">Hello admin</div> -->
            <div class="search-box">
                <!-- <input type="text" placeholder="Search" /> -->
                <!-- <button>Search</button> -->

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
            <h2 style="margin-left:50px;"> Courses </h2>
            <br>
            <table class="view-table">
                <tr>
                    <th>Course ID</th>
                    <th>Prerequisite Course Id</th>
                    <th>Department ID</th>
                    <th>Course Name</th>
                    <th>Course Credits</th>
                    <th class="text-center">Drop</th>
                </tr>
                <?php
                while ($row = mysqli_fetch_assoc($query)) {
                    $realPreCourseID = !empty($row["Prereq_Course_ID"]) ? $row["Prereq_Course_ID"] : "";
                    echo '<tr>';
                    echo '<td>' . $row["Course_ID"] . '</td>';
                    echo '<td>' . $realPreCourseID . '</td>';
                    echo '<td>' . $row["Dept_ID"] . '</td>';
                    echo '<td>' . $row["Course_Name"] . '</td>';
                    echo '<td>' . $row["Course_Credits"] . '</td>';
                    echo '<td><a href="deleteCourse.php?courseId=' . $row["Course_ID"] . '&type=all">Delete Row</a>&nbsp;&nbsp;<a href="deleteCourse.php?courseId=' . $row["Course_ID"] . '&type=pre" style="float: right">Delete Prerequisite</a></td>';
                    echo '</tr>';
                }
                ?>

            </table>
        </div>

    </div>

    <script src="../plugins/js/nav.js"></script>
    </div>
</body>

</html>
