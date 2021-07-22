<?php
session_start();
include '../connection.php';

$userId = $_SESSION["user_id"];
$crn = $_GET['crn'];
$cname = $_GET['cname'];

$sqlDays = "SELECT tsd.Week_Day FROM `section` as s LEFT JOIN `time_slot_day` as tsd ON tsd.Time_Slot_ID = s.Time_Slot_ID";
$sqlDays .= " WHERE s.CRN_Num = " . $crn;

$queryDays = mysqli_query($conn, $sqlDays);

$allowDays = [];
while ($rowDay = mysqli_fetch_assoc($queryDays)) {
    $allowDays[] = $rowDay['Week_Day'];
}

$sqlAtten = "SELECT * FROM enrollment WHERE CRN_Num = '".$crn."' and Date_Enrolled LIKE '%2021%'";
$queryAtten = mysqli_query($conn, $sqlAtten);

$courseStudents = array();

while($rowAtten = mysqli_fetch_assoc($queryAtten)){
    $sqlStudent = "SELECT * FROM users WHERE User_ID = '".$rowAtten["Student_ID"]."'";
    $queryStudent = mysqli_query($conn, $sqlStudent);
    $resultStudent = mysqli_fetch_assoc($queryStudent);

    array_push($courseStudents, $resultStudent);
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Island - Roster</title>

    <link rel="stylesheet" href="../plugins/css/index.css">
    <link rel="stylesheet" href="../plugins/css/navbar.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>
<div id="header">
        <div id="upper-header">
            <!-- <div class="user-name">Hello Faculty</div> -->
            <div class="search-box">
                <!-- <input type="text" placeholder="Search"/> -->
                <!-- <button>Search</button> -->

                <a href="../logout.php"><button>Logout</button></a>
            </div>
        </div>

    </div>

    <div id="main-section">

        <?php
            include 'sidebar.php';
        ?>


        <h2 style="margin-left:50px;"> Submit Attendance for '<?php echo $cname;?>' </h2>
        <?php
            if (count($allowDays) > 0) {
                echo 'Please select only these days (&nbsp;';

                foreach ($allowDays as $index => $allowDay) {
                    echo '<input type="hidden" class="allow-days" value="' . $allowDay . '">';
                    echo $allowDay;
                    if ($index < count($allowDays) - 1) {
                        echo ', ';
                    }
                }

                echo '&nbsp;)<br/>';
            }
        ?>
        <br>
        <form method="post" action="">
        <table class="view-table">

                <tr>
                    <th>S. No.</th>
                    <th>Student ID </th>
                    <th>Student Name</th>
                    <th>Attendance</th>
                </tr>
            <?php
                for($i=0; $i<count($courseStudents); $i++){
                    echo '<tr>';
                    echo '<form method="post" action="">';
                    echo '<td>'.($i+1).'</td>';
                    echo '<td>'.$courseStudents[$i]["User_ID"].'</td>';
                    echo '<td>'.$courseStudents[$i]["F_Name"].' '.$courseStudents[$i]["L_Name"].'</td>';
                    echo '<td>
                        <input type="date" name="date" onblur="return onCheckValidate(event)">
                        <input type="hidden" name="sid" value="'.$courseStudents[$i]["User_ID"].'">
                    <select name="dropdown'.$courseStudents[$i]["User_ID"].'">
                        <option value="Yes">Present</option>
                        <option value="No">Absent</option>
                    </select>
                    <input type="submit" name="'.$courseStudents[$i]["User_ID"].'" class="btn btn-primary">
                    </td>';

                    echo '</form>';

                    if(isset($_POST[$courseStudents[$i]["User_ID"]])){
                        $date = $_POST["date"];
                        $student = $_POST["sid"];
                        $present = $_POST["dropdown".$courseStudents[$i]["User_ID"]];

                        $sql = "INSERT INTO attendance (`CRN_Num`, `Student_ID`, `Date_Attended`, `Present`) VALUES('".$crn."', '".$student."', '".$date."', '".$present."')";
                        if ($conn->query($sql) === TRUE) {
                            echo '<script>alert("Attendace submitted")</script>';
                            // header('location: viewRosterCourse.php?crn='.$crn.'&cname='.$cname);
                          } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                          }
                    }
                    echo '</tr>';
                }
            ?>


        </table>
        </form>
    </div>


</div>
<!-- <script>

    var dayArr = ["Sunday", "Monday", "Tuesday", 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    function onCheckValidate(e) {
        e.preventDefault();

        let selDate = e.target.value;
        let realDate = new Date(selDate);
        let wNumber = realDate.getDay();

        let selDay = dayArr[wNumber];

        let existDayInputs = document.getElementsByClassName("allow-days");
        let existDays = [];

        let bExist = false;
        for (let i = 0; i < existDayInputs.length; i++) {
            if (selDay === existDayInputs[i].value) {
                bExist = true;
                break;
            }
        }

        if (bExist == false) {
            alert('Please select again!');
            e.target.value = '';
            return false;
        }

        return true;
    }
</script> -->
<script src="../plugins/js/viewRosterCourse.js"></script>
<script src="../plugins/js/nav.js"></script>
</body>
</html>
