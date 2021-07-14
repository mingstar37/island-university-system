<?php
session_start();
include '../connection.php';

$query = "";
$facultyId = "";
if (isset($_POST['submit'])) {
    $facultyId = $_POST['facultyId'];

    $sql = "SELECT `advisor`.*, fu.F_Name as f_F_Name, fu.L_Name as f_L_Name, su.F_Name as s_F_Name, su.L_Name as s_L_Name FROM `advisor` LEFT JOIN `users` as fu ON `fu`.User_ID = `advisor`.Faculty_ID";
    $sql .= " LEFT JOIN `users` as su ON su.User_ID = `advisor`.Student_ID WHERE `advisor`.`Faculty_ID` = " . $facultyId;
    $query = mysqli_query($conn, $sql);
}

if (isset($_POST['deleteRow'])) {
    $deleteRowId = $_POST['deleteRowId'];

    $sql = 'DELETE FROM `advisor` WHERE `id` = ' . $deleteRowId;
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
        <form method="post" action="">
            Enter Advisor's Faculty ID: <input type="text" name="facultyId" value="<?php echo $facultyId; ?>" placeholder="Faculty Id" required/> <br><br>
            <input type="submit" class="btn btn-primary" name="submit" value="View Advisees" />
        </form>

        <?php
            if ($query !== "") {
        ?>
                <h2 style="transform: translateX(35%)"> History </h2>
                <br>
                <table class="view-table">
                    <tr>
                        <th>Faculty ID</th>
                        <th>Faculty Name</th>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Time Of Advisement</th>
                        <th>Drop</th>
                    </tr>
                    <?php
                    while ($row = mysqli_fetch_assoc($query)) {
                        echo '<tr id="tr_' . $row['id'] . '">';
                        echo '<td>' . $row["Faculty_ID"] . '</td>';
                        echo '<td>' . $row["f_F_Name"]. ' ' . $row['f_F_Name'] . '</td>';
                        echo '<td>' . $row["Student_ID"] . '</td>';
                        echo '<td>' . $row["s_F_Name"]. ' ' . $row['s_F_Name'] . '</td>';
                        echo '<td>' . $row["Time_Of_Advisement"] . '</td>';
                        echo '<td><a href="javascript:void(0);" onclick="onRemoveRow('. $row['id'] . ');">Delete</a></td>';
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