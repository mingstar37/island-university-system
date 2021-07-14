<?php
session_start();
include '../connection.php';

$userId = $_SESSION["user_id"]];

$sql = "SELECT * FROM student_major WHERE Student_ID = '".$userId."'";
$query = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($query);

$type = $row['type'];
if (!empty($type)) {
    $type = explode(',', $type);
} else {
    $type = [];
}

$majorId = $row["Major_ID"];
$sql2 = "SELECT * FROM major WHERE Major_ID = '".$majorId."'";
$query2 = mysqli_query($conn,$sql2);
$row2 = mysqli_fetch_assoc($query2);

$deptId = $row2["Dept_ID"];
$sql3 = "SELECT * FROM department WHERE Dept_ID = '".$deptId."'";
$query3 = mysqli_query($conn,$sql3);
$row3 = mysqli_fetch_assoc($query3);

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
        <h1 id="banner"> Island - Student Major </h1>

        <?php
            include 'sidebar.php';
        ?>
        <div class="main-page">
                <br>
                Your Major is: <span style="font-weight:bold;font-size:20px;"><?php echo $row2["Major_Name"]?></span>
                <br><br>
                Department for your major is : <span style="font-weight:bold;font-size:20px;"><?php echo $row3["Dept_Name"]?></span>
                <br><br>

                <input type="checkbox" value="First Major" class="disabled" <?php if (in_array('First Major', $type)) echo 'checked'; ?>/>&nbsp;First Major<br/><br/>
                <input type="checkbox" value="Second Major" class="disabled" <?php if (in_array('Second Major', $type)) echo 'checked'; ?> />&nbsp;Second Major<br/><br/>
        </div>

    </div>
    <script src="../plugins/js/nav.js"></script>
</body>

</html>
