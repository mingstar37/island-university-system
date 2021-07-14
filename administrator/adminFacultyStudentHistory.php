<?php
include '../connection.php';
session_start();
$crns = array();
    $students = array();
$history = array();
$faculty_fetched = false;
if(isset($_POST["submit"])){
    $fId = $_POST["facultyId"];


    $crns = array();
    $students = array();

    $sqlSection = "SELECT * FROM section WHERE Faculty_ID='".$fId."'";
    $querySection = mysqli_query($conn,$sqlSection);
    // $rowStudent = mysqli_fetch_assoc($querySection);
    while( $rowSection = mysqli_fetch_assoc($querySection)){
        array_push($crns, $rowSection["CRN_Num"]);
    }

    for($i=0; $i< count($crns); $i++){
        $sqlStudents = "SELECT * FROM enrollment WHERE CRN_Num='".$crns[$i]."'";
        $queryStudents = mysqli_query($conn,$sqlStudents);

        while($rowStudents = mysqli_fetch_assoc($queryStudents)){
            array_push($students, $rowStudents["Student_ID"]);
        }
    }


    for($i=0; $i<count($students); $i++){
        $sqlHistory = "SELECT * FROM student_history WHERE Student_ID='".$students[$i]."'";
        $queryHistory = mysqli_query($conn,$sqlHistory);

        while($rowHistory = mysqli_fetch_assoc($queryHistory)){
            array_push($history, $rowHistory);
        }

    }



    $faculty_fetched = true;
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


            <h3 id="add-student-section"> View Student History </h3>
            <br>
           <form method="post" action="">
                Enter Faculty ID: <input type="text" name="facultyId" placeholder="Faculty Id" /> <br><br>
                <input type="submit" class="btn btn-primary" name="submit" value="View Student List" />
        <form>

        <br>
        <hr>
        <br>
        <?php
            if($faculty_fetched == true){
                ?>
                <table class="view-table">
                <tr>
                    <th>Student ID</th>
                    <th>CRN</th>
                    <th>Grade</th>
                    <th>Year</th>
                </tr>
            <?php
            for($i=0; $i<count($history); $i++){
                echo '<tr>';
                echo '<td>'.$history[$i]["Student_ID"].'</td>';
                echo '<td>'.$history[$i]["CRN_Num"].'</td>';
                echo '<td>'.$history[$i]["Grade"].'</td>';
                echo '<td>'.$history[$i]["year"].'</td>';
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
