<?php
session_start();
include '../connection.php';

$yearOptions = [2017, 2018, 2019, 2020, 2021];
$selYear = '';
$query = "";
$studentId = "";
if (isset($_POST['submit'])) {
    $selYear = $_POST['selYear'];
    $studentId = $_POST['studentId'];

    $sql = 'SELECT * FROM `student_history` as sh LEFT JOIN `users` ON users.User_ID = sh.Student_ID';
    $sql .= ' LEFT JOIN `section` ON `section`.CRN_Num = sh.CRN_Num LEFT JOIN course ON course.Course_ID = section.Course_ID WHERE sh.`year` = ' . $selYear;
    $sql .= ' AND sh.Student_ID = "' . $studentId . '"';
    $query = mysqli_query($conn, $sql);
}

if (isset($_POST['deleteRow'])) {
    $deleteRowId = $_POST['deleteRowId'];

    $sql = 'DELETE FROM `student_history` WHERE `id` = ' . $deleteRowId;
    $result = [
        'error' => '',
        'success' => true
    ];
    if ($conn->query($sql)) {
        $result['success'] = true;
    } else {
        $result['success'] = false;
        $result['error'] = 'Current row was not deleted.';
    }

    echo json_encode($result);
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Island - Advisees</title>

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
    include 'sidenav.php';
    ?>
    <div class="main-page">
        <form method="post" action="" style="padding-left: 100px">
            Select Year:
            <select name="selYear">
                <?php
                foreach ($yearOptions as $yearOption)
                {
                    ?>
                    <option value="<?php echo $yearOption; ?>" <?php if ($selYear == $yearOption) echo 'selected'; ?>><?php echo $yearOption; ?></option>
                <?php
                }
                ?>
            </select> &nbsp;&nbsp;
            Student ID:
            <input type="number" name="studentId" value="<?php echo $studentId; ?>" required>
            <br><br>
            <input type="submit" class="btn btn-primary" style="margin: auto;" name="submit" value="View History" />
        </form>

        <?php
        if ($query !== "") {
            ?>
            <br>
            <h2 style="transform: translateX(37%)"> History </h2>
            <table class="view-table">
                <tr>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>CRN Number</th>
                    <th>Course Name</th>
                    <th>Grade</th>
                    <th>Year</th>

                    <?php if ($selYear == 2021) {
                        echo '<th>Drop</th>';
                    }
                    ?>
                </tr>
                <?php
                while ($row = mysqli_fetch_assoc($query)) {
                    echo '<tr id="tr_' . $row['id'] . '">';
                    echo '<td>' . $row["Student_ID"] . '</td>';
                    echo '<td>' . $row["F_Name"] . ' ' . $row["L_Name"] . '</td>';
                    echo '<td>' . $row["CRN_Num"] . '</td>';
                    echo '<td>' . $row["Course_Name"] . '</td>';
                    echo '<td>' . $row["Grade"] . '</td>';
                    echo '<td>' . $row["year"] . '</td>';
                    if ($selYear == 2021) {
                        echo '<td><a href="javascript:void(0);" onclick="onRemoveRow('. $row['id'] . ');">Delete</a></td>';
                    }

                    echo '</tr>';
                }
                ?>
            </table>
            <?php
        }
        ?>


    </div>

</div>


</div>
<script>
    function onRemoveRow(delId) {
        var data = {
            deleteRowId: delId,
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