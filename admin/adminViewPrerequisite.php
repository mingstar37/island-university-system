<?php
session_start();
include '../connection.php';

if (isset($_POST['deleteRow'])) {
    $deleteRowId = $_POST['deleteRowId'];
    $courseId = $_POST['courseId'];

    $sqlPre = "UPDATE course SET Prereq_Course_ID = '' WHERE Course_ID = '".$courseId."'";

    $result = [
            'error' => '',
        'success' => true
    ];
    if ($conn->query($sqlPre)) {
        $sql = 'DELETE FROM `prerequisite` WHERE `id` = ' . $deleteRowId;

        $result['success'] = true;
    } else {
        $result['success'] = false;
        $result['error'] = 'Current row was not deleted.';
    }

    echo json_encode($result);
    exit;
}

$userId = $_SESSION["uId"];

$sql = "SELECT * FROM `prerequisite`";
$query = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Island - Departments</title>

    <link rel="stylesheet" href="../plugins/css/toastr.css">

    <link rel="stylesheet" href="../plugins/css/index.css">
    <link rel="stylesheet" href="../plugins/css/navbar.css">
    <link rel="stylesheet" href="../plugins/css/admin.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <script src="../plugins/js/toastr.js"></script>

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
        <h2 style="margin-left:50px;"> Prerequisites </h2>
        <br>
        <table class="view-table">
            <tr>
                <th>Course ID</th>
                <th>Prereq Course ID</th>
                <th>Grade Required</th>
                <th>Drop</th>
            </tr>
            <?php
            while ($row = mysqli_fetch_assoc($query)) {
                echo '<tr id="tr_' . $row["id"] . '">';
                echo '<td>' . $row["Course_ID"] . '</td>';
                echo '<td>' . $row["Prereq_Course_ID"] . '</td>';
                echo '<td>' . $row["Grade_Required"] . '</td>';
                echo '<td><a href="javascript:void(0)" onclick="onRemoveRow(' . $row['id'] . ', ' . $row["Course_ID"] . ')">Delete</a></td>';
                echo '</tr>';
            }
            ?>

        </table>

    </div>

</div>


</div>
<script>
    function onRemoveRow(delId, courseId) {
        var data = {
            deleteRowId: delId,
            courseId: courseId,
            deleteRow: true
        };

        $.ajax({
            method: "POST",
            url: window.location.href,
            data: data,
            success: function (res) {
                if (res !== undefined) {
                    let result = JSON.parse(res);

                    if (result.success == true) {
                        alert("Successfully deleted!");
                        $('#tr_' + delId).remove();
                    } else {
                        alert(result.error);
                    }
                }
            },
            complete: function () {
            }
        });
    }
</script>

<script src="../plugins/js/nav.js"></script>
</body>

</html>