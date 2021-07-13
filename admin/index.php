<?php
session_start();
include '../connection.php';

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

    <?php
    include 'header.php';
    ?>
    <div id="main-section">
        <h1 id="banner"> Island University - Admin </h1>

    <?php
        include 'sidenav.php';
        ?>

        <div class="main-page">
        </div>

    </div>
    </div>

    <script src="../plugins/js/nav.js"></script>
</body>

</html>
