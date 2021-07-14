<?php
session_start();
include '../connection.php';

    $history = array();
    $courseNames = array();
    $semesters = array();

    $userId = $_SESSION["user_id"]];
    $sql = "SELECT * FROM student_history WHERE Student_ID = '".$userId."' ORDER BY year DESC";
    $query = mysqli_query($conn,$sql);

    while($row = mysqli_fetch_assoc($query)){
        array_push($history, $row);

        $sqlSection = "SELECT * from section where CRN_Num = '".$row["CRN_Num"]."'";
        $querySection = mysqli_query($conn, $sqlSection);
        $rowSection = mysqli_fetch_assoc($querySection);

        if($rowSection["Sem_Term_Year"] == "Spring 2021"){
            array_push($semesters, "Spring");
        }else{
            array_push($semesters, "Fall");
        }

        $sqlCourse = "SELECT * FROM course WHERE Course_ID = '".$rowSection["Course_ID"]."'";
        $queryCourse = mysqli_query($conn, $sqlCourse);
        $rowCourse = mysqli_fetch_assoc($queryCourse);

        array_push($courseNames, $rowCourse["Course_Name"]);

    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Island University - Student</title>

    <link rel="stylesheet" href="../plugins/css/index.css">
    <link rel="stylesheet" href="../plugins/css/navbar.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>
    <div id="header">
        <div id="upper-header">
            <div class="search-box">
                <!-- <input type="text" placeholder="Search"/> -->
                <!-- <button>Search</button> -->
                <?php
                       echo $_SESSION["uemail"];
                ?>
                <a href="../logout.php"><button>Logout</button></a>
            </div>
        </div>
        <!-- <div id="lower-header">
            <a href="index.php"><div class="navi2"> Home </div></a>
            <a href="holds.php"><div class="navi2"> View Holds </div></a>
            <a href="addDropClass.php"><div class="navi2"> Add Drop Class </div></a>
            <a href="schedule.php"><div class="navi2"> Schedule </div></a>
            <a href="history.php"><div class="navi2"> History </div></a>
        </div> -->
    </div>



    <div id="main-section">
    <?php
            include 'sidebar.php';
        ?>
        <h1 id="banner"> Island University - Student Transcript </h1>
        <br>




            <table class="view-table">
                <tr>
                    <th>S. No.</th>
                    <th>Course Name</th>
                    <th>Grade</th>
                    <th>Semester </th>
                    <th>Year</th>

                </tr>
            <?php
                for($i=0; $i<count($history); $i++){
                    echo '<tr>';
                    echo '<td>'.($i+1).'</td>';
                    echo '<td>'.$courseNames[$i].'</td>';
                    echo '<td>'.$history[$i]["Grade"].'</td>';
                    echo '<td>'.$semesters[$i].'</td>';
                    echo '<td>'.$history[$i]["year"].'</td>';
                    echo '</tr>';
                }
            ?>

            </table>
    </div>

    <script src="../plugins/js/nav.js"></script>
</body>
</html>
