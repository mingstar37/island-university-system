<?php
include '../connection.php';
session_start();

$deptFaculty = array();
$student_fetched = false;
if(isset($_POST["submit"])){
    $sId = $_POST["studentId"];


    $sqlFaculty = "SELECT * FROM dept_faculty WHERE Faculty_ID='".$sId."'";
    $queryFaculty = mysqli_query($conn,$sqlFaculty);


    while($rowFaculty = mysqli_fetch_assoc($queryFaculty)){

        $deptId = $rowFaculty['Dept_ID'];
        $sql = "SELECT * FROM department WHERE Dept_ID ='".$deptId."'";
        $query = mysqli_query($conn,$sql);

        while($rowDepartment = mysqli_fetch_assoc($query)){
            array_push($deptFaculty, $rowDepartment);
        }


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
    <title>Island - Admin</title>

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


            <h3 id="add-student-section"> View Faculty Department </h3>
            <br>
           <form method="post" action="">
                Enter Faculty ID: <input type="text" name="studentId" placeholder="Faculty Id" /> <br><br>
                <input type="submit" class="btn btn-primary" name="submit" value="View Faculty" />
        <form>

        <br>
        <hr>
        <br>
        <?php
            if($student_fetched == true){
                ?>
                <table class="view-table">
                <tr>
                    <th>Department ID</th>
                    <th>Department Name</th>
                    <th>Building Name</th>
                </tr>
            <?php
            for($i=0; $i<count($deptFaculty); $i++){
                echo '<tr>';
                echo '<td>'.$deptFaculty[$i]["Dept_ID"].'</td>';
                echo '<td>'.$deptFaculty[$i]["Dept_Name"].'</td>';
                echo '<td>'.$deptFaculty[$i]["Building_Name"].'</td>';
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
    </div>

    <script src="../plugins/js/nav.js"></script>

</body>

</html>
