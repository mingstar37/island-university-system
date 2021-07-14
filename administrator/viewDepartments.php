<?php
session_start();
include '../connection.php';

$userId = $_SESSION["user_id"]];

$sql = "SELECT * FROM department";
$query = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Island - Departments</title>

    <link rel="stylesheet" href="../plugins/css/index.css">
    <link rel="stylesheet" href="../plugins/css/navbar.css">
    <link rel="stylesheet" href="../plugins/css/admin.css">
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
        include "sidenav.php";
        ?>

        <div class="main-page">
            <h2 style="margin-left:50px;"> Departments </h2>
            <br>
            <table class="view-table">
                <tr>
                    <th>Department ID</th>
                    <th>Department Name</th>
                    <th>Dept_Chair_First_Name</th>
                    <th>Dept_Chair_Last_Name</th>
                    <th>Building Name</th>
                    <th>Chair_Room_No</th>
                    <th>Drop</th>
                </tr>
                <?php
                while ($row = mysqli_fetch_assoc($query)) {
                    echo '<tr>';
                    echo '<td>' . $row["Dept_ID"] . '</td>';
                    echo '<td>' . $row["Dept_Name"] . '</td>';
                    echo '<td>' . $row["Dept_Chair_First_Name"] . '</td>';
                    echo '<td>' . $row["Dept_Chair_Last_Name"] . '</td>';
                    echo '<td>' . $row["Building_Name"] . '</td>';
                    echo '<td>' . $row["Chair_Room_No"] . '</td>';
                    echo '<td><a href="viewDepartmentFaculty.php?DeptId=' . $row["Dept_ID"] . '">View Faculty</a></td>';
                    echo '</tr>';
                }
                ?>

            </table>

        </div>

    </div>


    </div>

    <script src="../plugins/js/nav.js"></script>
</body>

</html>
