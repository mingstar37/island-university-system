<?php
session_start();
include '../connection.php';

$userId = $_SESSION["uId"];
if(isset($_POST["submit"])){
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];

    if($cpassword == $password){
        $sql = "UPDATE login_info SET User_Password = '".$password."' WHERE User_ID = '".$userId."'";
        if ($conn->query($sql) === TRUE) {
            echo 'Successfully updated Password';
    
        } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }else{
        echo "The passwords did not match";
    }
}


if(isset($_POST["submitEmail"])){
    $email = $_POST["email"];
    $cpassword = $_POST["cpassword"];

    if($cpassword == $password){
        $sql = "UPDATE login_info SET User_Email = '".$email."' WHERE User_ID = '".$userId."' and User_Password = '".$cpassword."'";
        if ($conn->query($sql) === TRUE) {
            echo 'Successfully updated Email';
    
        } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }else{
        echo "Something went wrong";
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - profile</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../plugins/css/admin.css">
    <link rel="stylesheet" href="../plugins/css/navbar.css">

</head>

<body>
    <div class="middle-section">
    <?php
        include 'sidenav.php'
    ?>

    <script src="../plugins/js/nav.js"></script>
</body>

</html>