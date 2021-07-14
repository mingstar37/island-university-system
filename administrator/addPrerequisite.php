<?php
include '../connection.php';
session_start();

if(isset($_POST["submitPrerequisite"])){
    $courseId = $_POST["courseId"];

    $prereqId = $_POST["prerequisiteId"];
    $gradeRequired = $_POST["gradeRequired"];

    $result = [
            'error' => '',
        'success' => true
    ];

    $sqlPre = "UPDATE course SET Prereq_Course_ID = '".$prereqId."' WHERE Course_ID = '".$courseId."'";
    if($conn->query($sqlPre) === TRUE){

        $sql = "INSERT INTO `prerequisite` (`Course_ID`, `Prereq_Course_ID`, `Grade_Required`) VALUES ('$courseId', '$prereqId', '$gradeRequired')";

        if ($conn->query($sql)) {
             $result['success'] = true;
        } else {
            $result['error'] = 'Prerequisite was not Added.';
            $result['success'] = false;
        }

    }else{
        $result['success'] = false;
        $result['error'] = 'Database issue!';
    }

    echo json_encode($result);
    exit;
}

$sqlCourse = "SELECT `Course_ID`, `Course_Name` FROM `course` WHERE `Course_ID` > 0";
$queryCourse = mysqli_query($conn, $sqlCourse);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Island</title>
<!--    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">-->

    <link rel="stylesheet" href="../plugins/css/index.css">
    <link rel="stylesheet" href="../plugins/css/navbar.css">
    <link rel="stylesheet" href="../plugins/css/admin.css">

    <link rel="stylesheet" href="../plugins/css/toastr.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

<!--    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>-->
    <script src="../plugins/js/toastr.js"></script>


    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />
    <style>
        ul li {
            list-style-type: none;
        }

        ul li a{
            padding: 20px !important;
            font-size: 16px !important;
        }
    </style>


</head>

<body>
     <?php
    include "header.php";
    ?>

    <div id="main-section">

        <?php
        include 'sidenav.php';
        ?>

        <div class="main-page">
            <h3 id="add-student-section"> Add Prerequisite </h3>
            <br>
            <form method="post" action="" onsubmit="return onAddPrequisite(event)">
                Select Course: <select class="selectpicker" name="courseId"  id="courseId" data-live-search="true" onchange="onSelectedCourseID(event)">
                    <option value="">Select Course</option>
                    <?php
                        while ($rowCourse = mysqli_fetch_assoc($queryCourse)) {
                            echo "<option value='" . $rowCourse['Course_ID'] . "'>" . $rowCourse['Course_Name'] . "</option>";
                        }
                    ?>
                </select>
                <br/>
                <br/>
                Enter Prerequisite ID: <input type="text" id="prerequisiteId" name="prerequisiteId" placeholder="Prerequisite Course ID" required/> <br><br>
                Enter Grade Required <input type="text" name="gradeRequired" placeholder="Grade Required..." id="gradeRequired" required><br/><br/>
                <input type="submit" class="btn btn-primary" name="submitPrerequisite" value="Add Prerequisite" />
                </form>

                    <br>
                    <hr>


        </div>
    </div>


    <script>

        $(document).ready(function () {
            $('.selectpicker').selectpicker();
        })
        function onAddPrequisite(event) {
            event.preventDefault();

            let courseId = $('#courseId').val();
            if (courseId == 0) {
                alert('Select Course!');
                return;
            }

            var data = {
                courseId: courseId,
                prerequisiteId: $('#prerequisiteId').val(),
                gradeRequired: $('#gradeRequired').val(),
                submitPrerequisite: true
            }

            $.ajax({
                method: "POST",
                url: window.location.href,
                data: data,
                success: function (res) {
                    if (res !== undefined) {
                        let result = JSON.parse(res);

                        if (result.success == true) {
                            toastr.success('Successfully added!');
                            $('#courseId').val('');
                            $('#prerequisiteId').val('');
                            $('#gradeRequired').val('');

                        } else {
                            toastr.error(result.error);
                        }
                    }
                },
                complete: function () {
                }
            });
        }
    </script>
    <script src="../plugins/js/nav.js"></script>

</body>

</html>
