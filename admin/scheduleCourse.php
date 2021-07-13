<?php
include '../connection.php';
session_start();

$course_added = false;

$sqlPeriod = "SELECT * FROM `period` ORDER BY `End_Time`";
$queryPeriod = mysqli_query($conn, $sqlPeriod);

$queryValidate = "";

function validate($postArr, $conn) {
    $facultyId = $postArr['facultyId'];
    $days = $postArr['days'];
    $strDays = "";

    foreach ($days as $dayIndex => $day) {
        $strDays .= "'" . $day . "'";
        if ($dayIndex < count($days) - 1) {
            $strDays .= ", ";
        }
    }

    $periodId = $postArr['periodId'];
    $sem = $postArr['sem'];
    $roomNum = $postArr['roomNum'];

    $sqlValidate = "SELECT * FROM `section` as sc LEFT JOIN `time_slot_period` as tsp ON tsp.Time_Slot_ID = sc.Time_Slot_ID";
    $sqlValidate .= " LEFT JOIN `period` as p ON p.Period_No = tsp.Period_No";
    $sqlValidate .= " LEFT JOIN `time_slot` ts ON ts.Time_Slot_ID = sc.Time_Slot_ID";
    $sqlValidate .= " LEFT JOIN `time_slot_day` tsd ON tsd.Time_Slot_ID = ts.Time_Slot_ID";
    $sqlValidate .=" WHERE sc.`Faculty_ID` = '" . $facultyId . "' AND sc.Sem_Term_Year = '" . $sem . "' AND sc.Room_Num = '" . $roomNum . "'";
    $sqlValidate .= " AND tsp.Period_No = '" . $periodId . "'";
    $sqlValidate .= " AND tsd.Week_Day IN (" . $strDays . ")";
    $queryValidate = mysqli_query($conn, $sqlValidate);

    if (mysqli_num_rows($queryValidate) > 0) {
        return false;
    } else {
        return true;
    }
}

if (isset($_POST["checkValidate"])) {
    $bResult = validate($_POST, $conn);

    $result = [
        'error' => '',
        'success' => true
    ];

    if ($bResult == true) {
        $result['success'] = true;
    } else {
        $result['success'] = false;
        $result['error'] = "Already existing...";
    }

    echo json_encode($result);
    exit;
}

if (isset($_POST["validate"])) {
    $facultyId = $_POST["facultyId"];
    $periodId = isset($_POST["periodId"]) ? $_POST["periodId"] : 0;

    $validateHtml = "";

    $sqlSection = "Select * from section where Faculty_ID = '".$facultyId."'";
    $querySection = mysqli_query($conn,$sqlSection);

    $CourseIds = array();
    $crns = array();
    $periods = array();

    while($row = mysqli_fetch_assoc($querySection)){
        array_push($CourseIds, $row["Course_ID"]);
        array_push($crns, $row);

        $sqlTimes = "SELECT * FROM time_slot_period WHERE Time_Slot_ID = '".$row["Time_Slot_ID"]."'";
        $querytimes = mysqli_query($conn, $sqlTimes);
        $rowTimes = mysqli_fetch_assoc($querytimes);
        array_push($periods, $rowTimes["Period_No"]);
    }

    echo '<tr>
                    <th>Course ID</th>
                    <!-- <th>Prerequisite Course Id</th> -->
                    <th>Faculty</th>
                    <th>Room Number</th>
                    <th>Semester</th>
                    <th>Section</th>
                    <th>Days</th>
                    <th>Time</th>
                </tr>';

    for ($i = 0; $i < count($CourseIds); $i++) {
        $course = $CourseIds[$i];
        $sql = "SELECT * FROM course WHERE Course_ID = '" . $course . "'";
        $query1 = mysqli_query($conn, $sql);
        echo '<tr>';
        echo '<td>' . $result["Course_ID"] . '</td>';
        // echo '<td>'.$result["Prereq_Course_ID"].'</td>';


        $sqlFac = "SELECT * FROM users where User_ID = '" . $crns[$i]["Faculty_ID"] . "'";
        $query = mysqli_query($conn, $sqlFac);
        $rowFac = mysqli_fetch_assoc($query);

        echo '<td>' . $rowFac["F_Name"] . ' ' . $rowFac["L_Name"] . '</td>';
        echo '<td>' . $crns[$i]["Room_Num"] . '</td>';
        echo '<td>' . $crns[$i]["Sem_Term_Year"] . '</td>';
        echo '<td>' . $crns[$i]["Section"] . '</td>';

        $days = array();
        $sqlDays = "SELECT * FROM time_slot_day where Time_Slot_ID = '" . $crns[$i]["Time_Slot_ID"] . "'";
        $queryDays = mysqli_query($conn, $sqlDays);
        while ($rowDays = mysqli_fetch_assoc($queryDays)) {
            array_push($days, $rowDays["Week_Day"]);
        }

        $List = implode(', ', $days);
        echo '<td>';
        print_r($List);
        echo '</td>';


        $sqlPeriod = "SELECT * FROM period WHERE Period_No = '" . $periods[$i] . "'";
        $queryPeriod = mysqli_query($conn, $sqlPeriod);
        $rowPeriod = mysqli_fetch_assoc($queryPeriod);

        echo '<td>' . $rowPeriod["Start_Time"] . ' ' . $rowPeriod["End_Time"] . '</td>';

        echo '</tr>';
    }

    exit;
}


if(isset($_POST["submit"])){
    $courseId = $_POST["courseId"];
    $facultyId = $_POST["facultyId"];
    $prereqId = $_POST["prereqId"];
    $facultyId = $_POST["facultyId"];
    $days = $_POST["days"];
    $periodId = $_POST["periodId"];

    $roomNum = $_POST['roomNum'];
    $buildingName = $_POST['buildingName'];
    $aseat = $_POST['aseat'];
    $sem = $_POST["sem"];
    $section = $_POST["section"];

    $bValidate = validate($_POST, $conn);

    $result = [
            'error' => '',
        'success' => true
    ];

    if ($bValidate === false) {
        $result['success'] = false;
        $result['error'] = "Already existing...";


    } else {
        // $sql2 = "INSERT INTO course VALUES (".$dId.",'','".$df."','".$dl."','".$dbn."')";

        $sql = "INSERT INTO `time_slot`(`val`) VALUES ('')";
        if ($conn ->query($sql)) {
            $timeSlotId = $conn->insert_id;

            foreach ($days as $day) {
                $sql = "INSERT INTO `time_slot_day` (`Time_Slot_ID`, `Week_Day`) VALUES (" . $timeSlotId . ", '" . $day . "')";
                if (!$conn->query($sql)) {
                    die('error here');
                }
            }

            $sql = "INSERT INTO `time_slot_period` (`Time_Slot_ID`, `Period_No`) VALUES ('" . $timeSlotId . "', '" . $periodId . "')";
            if (!$conn->query($sql)) {
                die('error');
            }

            $sqlMain = "INSERT INTO `section` (`CRN_Num`, `Course_ID`, `Faculty_ID`, `Room_Num`, `Building_Name`, `Available_Seat`, `Sem_Term_Year`, `Time_Slot_ID`, `Section`)";
            $sqlMain .= " VALUES('0', '$courseId', '$facultyId', '$roomNum', '$buildingName', '$aseat', '$sem', '$timeSlotId', '$section')";

            if ($conn->query($sqlMain)) {
                $result['success'] = true;
            } else {
                $result['success'] = false;
                $result['error'] = "Creating error!";
            }
        }
    }

    echo json_encode($result);
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Island</title>

    <link rel="stylesheet" href="../plugins/css/index.css">
    <link rel="stylesheet" href="../plugins/css/navbar.css">
    <link rel="stylesheet" href="../plugins/css/admin.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>

<body>
    <div id="header">
        <div id="upper-header">
            <!-- <div class="user-name"><a href="profile.php">Hello admin</a></div> -->
            <div class="search-box">
                <!-- <input type="text" placeholder="Search" />
                <button>Search</button> -->

                <a href="../logout.php"><button>Logout</button></a>
            </div>
        </div>
        <div id="lower-header">
            <a href="index.php">
                <div class="navi"> Home </div>
            </a>
            <a href="departments.php">
                <div class="navi"> Departments </div>
            </a>
            <a href="addStudent.php">
                <div class="navi"> Students </div>
            </a>
            <a href="enrolled.php">
                <div class="navi"> Academics </div>
            </a>
            <a href="courses.php">
                <div class="navi"> Courses </div>
            </a>
            <a href="faculty.php">
                <div class="navi"> Faculty </div>
            </a>
            <a href="researcher.php">
                <div class="navi"> Researcher </div>
            </a>
        </div>
    </div>


    <div id="main-section">

        <?php
            include 'sidenav.php';
        ?>

        <div class="main-page">
            <h3 id="add-student-section"> Create Section </h3>
            <br>
            <div class="row" style="margin: 0">

                <div class="col-lg-5">
                    <?php
                    if ($course_added) {
                        echo "Department Added Successfully";
                    }
                    ?>
                    <form method="post" action="" onsubmit="return onCreateSection(event)">

                        Faculty ID: <input type="number" name="facultyId" id="facultyId" onblur="onValidate();" placeholder="Faculty ID" required/> <br><br>
                        Select Days: <div style="padding-left: 100px">
                            <input type="checkbox" class="days" name="days[]" value="Monday"> Monday <br>
                            <input type="checkbox" class="days" value="Tuesday"> Tuesday<br>
                            <input type="checkbox" class="days" value="Wednesday"> Wednesday<br>
                            <input type="checkbox" class="days" value="Thursday"> Thursday<br>
                            <input type="checkbox" class="days" value="Friday"> Friday<br>
                            <input type="checkbox" class="days" value="Saturday"> Saturday<br>
                            <input type="checkbox" class="days" value="Sunday"> Sunday<br>
                        </div>

                        <br/><br/>
                        Select Time:
                        <select name="periodId">
                            <?php
                            while ($rowPeriod = mysqli_fetch_assoc($queryPeriod)) {
                                ?>
                                <option value="<?php echo $rowPeriod['Period_No']; ?>"><?php echo $rowPeriod['Start_Time'] . ' ~ ' . $rowPeriod['End_Time']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <br/><br/>
                        Semester:
                        <select name="sem">
                            <option value="Spring 2021">Spring 2021</option>
                            <option value="Fall 2021">Fall 2021</option>
                        </select> <br/><br/>
                        Room Number: <input type="text" id="roomNumber" name="roomNum" placeholder="Room Number" required/>
                        &nbsp;&nbsp;<button type="button" class="btn btn-outline-info btn-sm" onclick="onCheckValidate(event)">Check Validate</button>
                        <br/>
                        <br/>
                        Course Id: <input type="number" name="courseId" placeholder="Course Id" required/> <br><br>
                        Pre requisite Course ID: <input type="number" name="prereqId" placeholder="Pre requisite Course ID" required/> <br><br>
                        Building Name: <input type="text" name="buildingName" placeholder="Building Name" required/> <br><br>

                        Available Seat: <input type="text" name="aseat" placeholder="Available Seat" required/> <br><br>
                        Section: <input type="text" name="section" placeholder="Section" required/> <br><br>

                        <input type="submit" class="btn btn-primary" name="submit" value="Create Section" />
                    </form>

                    <br><br>
                </div>
                <div class="col-lg-7">
                    <table id="validateTable" style="width: 100%; margin: 0" class="view-table">
                    </table>
                </div>
            </div>

        </div>
    </div>


    <script>
        function getFormData($form){
            var unindexed_array = $form.serializeArray();
            var indexed_array = {};

            $.map(unindexed_array, function(n, i){
                indexed_array[n['name']] = n['value'];
            });

            return indexed_array;
        }

        function onCreateSection(e) {
            e.preventDefault();

            let selectedDays = document.getElementsByClassName('days');
            let daysArr = [];
            for(let i = 0; i < selectedDays.length; i++) {
                if (selectedDays[i].checked === true) {
                    daysArr.push(selectedDays[i].value);
                }
            }

            if (daysArr.length < 1) {
                alert('Please select days');
                return ;
            }

            let formData = $('form');

            formData = getFormData(formData);
            formData['days'] = daysArr;
            formData['submit'] = true;

            $.ajax({
                method: "POST",
                url: window.location.href,
                data: formData,
                dataType: 'json',
                success: function (res) {
                    if (res !== undefined) {
                        if (res.success == true) {
                            alert("successfully Added!");
                            // setTimeout(function () {
                            //     window.location.href = "./viewSections.php";
                            // }, 1000);
                        } else {
                            alert(res.error);
                        }
                    }
                },
                complete: function () {
                }
            });

            return false;
        }

        function onCheckValidate(event) {
            event.preventDefault();

            if ($('#facultyId').val() == "" || $('#facultyId').val() == undefined) {
                alert('Insert Faculty Id!');
                return;
            }

            let selectedDays = document.getElementsByClassName('days');
            let daysArr = [];
            for(let i = 0; i < selectedDays.length; i++) {
                if (selectedDays[i].checked === true) {
                    daysArr.push(selectedDays[i].value);
                }
            }

            if (daysArr.length < 1) {
                alert('Please select days');
                return ;
            }

            if ($('#roomNumber').val() == "" || $('#roomNumber').val() == undefined) {
                alert('Insert Room Number!');
                return;
            }


            let formData = $('form');

            formData = getFormData(formData);
            formData['days'] = daysArr;
            formData['checkValidate'] = true;

            $.ajax({
                method: "POST",
                url: window.location.href,
                data: formData,
                dataType: 'json',
                success: function (res) {
                    if (res !== undefined) {
                        if (res.success == true) {
                            alert("You can add!");
                        } else {
                            alert(res.error);
                        }


                    }
                },
                complete: function () {
                }
            });

            return false;
        }

        function onValidate() {
            let facultyId = $('#facultyId').val();
            let periodId = $('#periodId').val();

            var data = {
                facultyId,
                periodId,
                validate: true
            };

            $.ajax({
                method: "POST",
                url: window.location.href,
                data: data,
                success: function (res) {
                    $('#validateTable').empty();
                    $('#validateTable').append(res);
                },
                complete: function () {
                }
            });
        }

    </script>
    <script src="../plugins/js/nav.js"></script>

</body>

</html>