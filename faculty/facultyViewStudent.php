<?php
include '../connection.php';
session_start();

$fullPart = "Full Time";
$advisors = array();
$faculties = array();
$coursesTrans = array();
$crnsTrans = array();
$gradeYear = array();
$coursesEnrolled = array();
$crnsEnrolled = array();
$gradeYear2 = array();


$student_fetched = false;
if(isset($_POST["submit"])){
    $sId = $_POST["studentId"];

    $sqlStudent = "SELECT * FROM student WHERE Student_ID='".$sId."'";
    $queryStudent = mysqli_query($conn,$sqlStudent);
    $rowStudent = mysqli_fetch_assoc($queryStudent);

    $sql = "SELECT * FROM users where User_ID = '".$sId."'";
    $query = mysqli_query($conn,$sql);
    $rowUser = mysqli_fetch_assoc($query);



    $sqlUnder = "SELECT * FROM undergraduate WHERE Student_ID = '".$sId."'";
    $queryUnder = mysqli_query($conn,$sqlUnder);
    $rowUnder = mysqli_fetch_assoc($queryUnder);


    $sqlGrad = "SELECT * FROM graduate WHERE Student_ID = '".$sId."'";
    $queryGrad = mysqli_query($conn,$sqlGrad);
    $rowGrad = mysqli_fetch_assoc($queryGrad);



    if($rowStudent["Student_Type"] == 'Undergraduate'){
        $fullPart = $rowUnder["Undergrad_Type"];
    }else{
        $fullPart = $rowGrad["Grad_Type"];
    }

    $sqlHolds = "SELECT * FROM student_holds WHERE Student_ID = '".$sId."'";
    $queryHolds = mysqli_query($conn,$sqlHolds);








    // major
    $sqlMajor = "SELECT * FROM student_major WHERE Student_ID = '".$sId."'";
    $queryMajor = mysqli_query($conn,$sqlMajor);
    $rowMajor = mysqli_fetch_assoc($queryMajor);

    $majorId = $rowMajor["Major_ID"];

    $sqlMaj = "SELECT * FROM major WHERE Major_ID = '".$majorId."'";
    $queryMaj = mysqli_query($conn,$sqlMaj);
    $rowMaj = mysqli_fetch_assoc($queryMaj);



    // advisor
    $sqlAdvisor = "SELECT * FROM advisor WHERE Student_ID = '".$sId."'";
    $queryAdvisor = mysqli_query($conn,$sqlAdvisor);


    while ($rowAdvisor = mysqli_fetch_assoc($queryAdvisor)){
        $advisorId = $rowAdvisor["Faculty_ID"];
        array_push($advisors, $rowAdvisor);

        $sql2 = "SELECT * FROM users WHERE User_ID = '".$advisorId."'";
        $query2 = mysqli_query($conn,$sql2);

        $rowFaculty = mysqli_fetch_assoc($query2);
        array_push($faculties, $rowFaculty);
    }


    // transcript
    $sqlTrans = "SELECT * FROM student_history WHERE Student_ID = '".$sId."'";
    $queryTrans = mysqli_query($conn,$sqlTrans);



    while($rowTrans = mysqli_fetch_assoc($queryTrans)){
        $sqlTrans2 = "SELECT * FROM section WHERE CRN_Num = '".$rowTrans['CRN_Num']."'";
        $queryTrans2 = mysqli_query($conn, $sqlTrans2);
        $rowTrans2 = mysqli_fetch_assoc($queryTrans2);
        array_push($gradeYear, $rowTrans);
        array_push($crnsTrans, $rowTrans2["Course_ID"]);
    }


    for($i=0; $i< count($crnsTrans); $i++){
        $sqlTrans3 = "SELECT * FROM course WHERE Course_ID = '".$crnsTrans[$i]."'";
        $queryTrans3 = mysqli_query($conn, $sqlTrans3);
        $rowTrans3 = mysqli_fetch_assoc($queryTrans3);
        array_push($coursesTrans, $rowTrans3["Course_Name"]);
    }


    // enrolled
    $sqlEnrolled = "SELECT * FROM enrollment WHERE Student_ID = '".$sId."'";
    $queryEnrolled = mysqli_query($conn,$sqlEnrolled);

    while($rowEnrolled = mysqli_fetch_assoc($queryEnrolled)){
        $sqlEnrolled2 = "SELECT * FROM section WHERE CRN_Num = '".$rowEnrolled['CRN_Num']."'";
        $queryEnrolled2 = mysqli_query($conn, $sqlEnrolled2);
        $rowEnrolled2 = mysqli_fetch_assoc($queryEnrolled2);
        array_push($gradeYear2, $rowEnrolled);
        array_push($crnsEnrolled, $rowEnrolled2["Course_ID"]);
    }


    for($i=0; $i< count($crnsEnrolled); $i++){
        $sqlEnrolled3 = "SELECT * FROM course WHERE Course_ID = '".$crnsEnrolled[$i]."'";
        $queryEnrolled3 = mysqli_query($conn, $sqlEnrolled3);
        $rowEnrolled3 = mysqli_fetch_assoc($queryEnrolled3);
        array_push($coursesEnrolled, $rowEnrolled3["Course_Name"]);
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


        <?php
            include 'sidebar.php';
        ?>
        <div class="main-page">


            <h3 id="add-student-section"> View Student </h3>
            <br>
           <form method="post" action="">
                Enter Student ID: <input type="text" name="studentId" placeholder="Student Id" /> <br><br>
                <input type="submit" class="btn btn-primary" name="submit" value="View Student" />
        <form>

        <br>
        <hr>
        <br>
        <?php
            if($student_fetched == true){
                ?>
                <br>
                Name: <span style="font-weight:bold;font-size:20px;"><?php echo $rowUser["F_Name"].' '.$rowUser["L_Name"] ?></span> <br>
                Current Year and Semester: <span style="font-weight:bold;font-size:20px;">Spring 2021</span> <br>
                Student Type: <span style="font-weight:bold;font-size:20px;"><?php  echo $fullPart ?></span> <br>
                Student Major: <span style="font-weight:bold;font-size:20px;"><?php  echo $rowMaj["Major_Name"] ?></span> <br>

                <br>
                <br>
                <table class="view-table">
                <tr>
                    <th>Student GPA</th>
                    <th>Student Type</th>
                </tr>
            <?php
                    echo '<tr>';
                    echo '<td>'.$rowStudent["Student_GPA"].'</td>';
                    echo '<td>'.$rowStudent["Student_Type"].'</td>';
                    echo '</tr>';
            ?>

        </table>



                <br>
                <br>
                <h3>Student Holds</h3>
                <table class="view-table">
                <tr>
                    <th>S.No.</th>
                    <th>Hold Date</th>
                    <th>Hold Type</th>
                </tr>
            <?php
                $counter = 1;
                while($rowHolds = mysqli_fetch_assoc($queryHolds)){
                    echo '<tr>';
                    echo '<td>'.$counter.'</td>';
                    echo '<td>'.$rowHolds["Hold_Date"].'</td>';
                    echo '<td>'.$rowHolds["Hold_Type"].'</td>';
                    echo '</tr>';

                    $counter++;
                }

            ?>

        </table>


        <br>
        <br>
        <h3>Advisors</h3>
        <?php
                    for($i=0; $i<count($advisors); $i++){
                        ?>
                        Advisor Name: <span style="font-weight:bold;font-size:20px;"><?php echo $faculties[$i]["F_Name"].' '.$faculties[$i]["L_Name"] ?></span>
                        <br>
                        Time of Advisement: <span style="font-weight:bold;font-size:20px;"><?php echo $advisors[$i]["Time_Of_Advisement"]?></span>
                        <br><br>
                        <?php
                    }

                ?>

        <br><br>

        <h3>Student Transcript</h3>

        <table class="view-table">
            <tr>
                    <th>S.No.</th>
                    <th>Course Name</th>
                    <th>Grade</th>
                    <th>Year</th>
            </tr>
        <?php

           for($i=0; $i<count($gradeYear); $i++){
                echo '<tr>';
                echo '<td>'.($i+1).'</td>';
                echo '<td>'.$coursesTrans[$i].'</td>';
                echo '<td>'.$gradeYear[$i]["Grade"].'</td>';
                echo '<td>'.$gradeYear[$i]["year"].'</td>';
                echo '</tr>';
            }
        ?>

        </table>

        <br>
        <br>

        <h3>Student Enrolled Courses</h3>

        <table class="view-table">
            <tr>
                    <th>Student ID</th>
                    <th>Course Name</th>
                    <th>Date Enrolled</th>
                    <th>Letter Grade</th>
            </tr>
        <?php
            for($i=0; $i<count($gradeYear2); $i++){
                echo '<tr>';
                echo '<td>'.($i+1).'</td>';
                echo '<td>'.$coursesEnrolled[$i].'</td>';
                echo '<td>'.$gradeYear2[$i]["Date_Enrolled"].'</td>';
                echo '<td>'.$gradeYear2[$i]["Letter_Grade"].'</td>';

                echo '</tr>';
            }
        ?>

        </table>

        <br><br>
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
