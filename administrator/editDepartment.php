<?php
include '../connection.php';
session_start();

$dept_added = false;

if(isset($_POST["edit"])){
    $dId = $_POST['dId'];
    $dn = $_POST['dn'];
    $df = $_POST['df'];
    $dl = $_POST['dl'];
    $dbn = $_POST['dbn'];
    $dcr = $_POST['dcr'];
    $sql3 = "UPDATE department SET Dept_Name = '".$dn."', Dept_Chair_First_Name = '".$df."', Dept_Chair_Last_Name = '".$dl."', Building_Name = '".$dbn."', Chair_Room_No = '".$dcr."' WHERE Dept_ID = '".$dId."'";

    if ($conn->query($sql3) === TRUE) {
        // echo 'Successfully updated department';
        echo '<script>alert("Department Updated Successfully")</script>';
        header('location:departments.php');
    } else {
      echo "Error: " . $sql3 . "<br>" . $conn->error;
    }
    $dept_added = true;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Island - Edit Department</title>

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


            <h3 id="add-student-section"> Edit Department </h3>
            <br>
            <?php
            if ($dept_added) {
                echo "Department Edited Successfully";
            }
            ?>
           <form method="post" action="">
                Department Id: <input type="text" name="dId" placeholder="Department Id" /> <br><br>
                Department Name: <input type="text" name="dn" placeholder="Department Name" /> <br><br>
                Dept_Chair_First_Name: <input type="text" name="df" placeholder="Department Chair_First_Name" /> <br><br>
                Dept_Chair_Last_Name: <input type="text" name="dl" placeholder="Department Chair_Last_Name" /> <br><br>
                Building Name: <input type="text" name="dbn" placeholder="Building Name" /> <br><br>
                Chair_Room_No: <input type="text" name="dcr" placeholder="Chair_Room_No" /> <br><br>
                <input type="submit" class="btn btn-primary" name="edit" value="Edit Department" />
        <form>
        </div>
    </div>


    </div>
    </div>

    <script src="../plugins/js/nav.js"></script>

</body>

</html>
