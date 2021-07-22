<?php
session_start();
if (empty($_SESSION['user_id']) || empty($_SESSION['user_email']) || empty($_SESSION['type'])) {
    header('location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

include '../connection.php';

// courses enrolled count
$sql = "SELECT * FROM enrollment as e LEFT JOIN student as s ON s.id = e.student_id WHERE s.user_id = '$user_id' AND e.date_enrolled LIKE '%2021%'";
$query = mysqli_query($conn, $sql);
$enrolled_count = mysqli_num_rows($query);

// part, undergraduate status
$sql = "SELECT student_type, undergrad_type FROM student WHERE user_id = '$user_id'";
$query = mysqli_query($conn, $sql);

$row = mysqli_fetch_assoc($query);

$student_type = "";
if ($row['student_type'] == 'graduate') {
    $student_type = "Graduage";
} else {
    $student_type = "Undergraduate";
}

$undergrad_type = "";
if ($row['undergrad_type'] == 'part_time') {
    $undergrad_type = "Part";
} else {
    $undergrad_type = "Full";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Island - Faculty</title>

    <link rel="stylesheet" href="../plugins/css/index.css">
    <link rel="stylesheet" href="../plugins/css/navbar.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>
<?php
include "./header.php";
?>

<div id="main-section">
    <h1 id="banner"> Island University - Faculty </h1>

    <?php
    include './sidebar.php';
    ?>


    <div id="login-section">
        <h4>Welcome Student!</h4>
        <h5>Number of courses enrolled: <?php echo $enrolled_count; ?></h5>
        <h5>You are: <?php echo $undergrad_type; ?> &nbsp;<?php echo $student_type; ?></h5>

    </div>
</div>
</div>


<script src="../plugins/js/nav.js"></script>
</body>
</html>
