<?php
include '../connection.php';
session_start();


$student_fetched = false;
if (isset($_POST["submit"])) {

    $hold_id = $_POST["holdId"];
    $hold_type = $_POST["holdType"];
    $sqlMajor = "INSERT INTO hold (`Hold_ID`, `Hold_Type`) VALUES ('".$hold_id."', '".$hold_type."')";
    if ($conn->query($sqlMajor) === TRUE) {
        // echo 'Successfully added minor';
        header('location:adminAddHold.php');
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }



    $student_fetched = true;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Island - Add Department</title>

    <link rel="stylesheet" href="../plugins/css/index.css">
    <link rel="stylesheet" href="../plugins/css/navbar.css">
    <link rel="stylesheet" href="../plugins/css/admin.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>

<body>
     <?php
    include "header.php";
    ?>


    <div id="main-section">
        <!-- <h1 id="banner"> Island University - Admin </h1> -->

        <?php
        include "sidenav.php";
        ?>
        <div class="main-page">


            <h3 id="add-student-section"> Add Hold </h3>
            <br>
            <form method="post" action="">
                Hold ID: <input type="text" name="holdId" placeholder="Hold ID" /> <br><br>
                Hold Type: <input type="text" name="holdType" placeholder="Hold Type" /> <br><br>
                <input type="submit" class="btn btn-primary" name="submit" value="Add Hold" />
                <form>

        </div>
    </div>


    </div>
    </div>

    <script src="../plugins/js/nav.js"></script>

</body>

</html>
