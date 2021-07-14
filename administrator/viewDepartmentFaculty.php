<?php
session_start();
include '../connection.php';

$userId = $_SESSION["user_id"]];
$deptId = $_GET["DeptId"];

$facultyIds = array();
$facultyNames = array();
$facultyRanks = array();
$facultyTypes = array();

$sqlDept = "SELECT * FROM department WHERE Dept_ID = '".$deptId."'";
$queryDept = mysqli_query($conn, $sqlDept);
$rowDept = mysqli_fetch_assoc($queryDept);


$sql = "SELECT * FROM dept_faculty WHERE Dept_ID = '".$deptId."'";
$query = mysqli_query($conn, $sql);

while($row = mysqli_fetch_assoc($query)){
    $facId = $row["Faculty_ID"];

    $sqlNames = "SELECT * FROM users WHERE User_ID = '".$facId."'";
    $queryNames = mysqli_query($conn, $sqlNames);

    $rowName = mysqli_fetch_assoc($queryNames);

    $sqlFaculty = "SELECT * FROM faculty WHERE Faculty_ID = '".$facId."'";
    $queryFaculty = mysqli_query($conn, $sqlFaculty);
    $rowFaculty = mysqli_fetch_assoc($queryFaculty);

    array_push($facultyIds, $facId);
    array_push($facultyNames, $rowName["F_Name"].' '.$rowName["L_Name"]);
    array_push($facultyRanks, $rowFaculty["Faculty_Rank"]);

    if($rowFaculty["Faculty_Type"] == 'Part_T_Faculty'){
        array_push($facultyTypes, "Full Time");
    }
    else{
        array_push($facultyTypes, "Part Time");
    }



}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Island - Departments</title>

    <link rel="stylesheet" href="../plugins/css/index.css">
    <link rel="stylesheet" href="../plugins/css/navbar.css">
    <link rel="stylesheet" href="../plugins/css/admin.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
</head>

<body>
    <div id="header">
        <div id="upper-header">
            <!-- <div class="user-name">Hello admin</div> -->
            <div class="search-box">
                <!-- <input type="text" placeholder="Search" /> -->
                <!-- <button>Search</button> -->

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
            <h2 style="margin-left:50px;"> Faculty list for '<?php  echo $rowDept["Dept_Name"]?>' </h2>
            <br>
            <table class="view-table">
                <tr>
                    <th>Faculty ID</th>
                    <th>Faculty Name</th>
                    <th>Faculty Rank</th>
                    <th>Faculty Type</th>

                </tr>
                <?php
                    for($i=0; $i<count($facultyIds); $i++) {
                    echo '<tr>';
                    echo '<td>' . $facultyIds[$i] . '</td>';
                    echo '<td>' . $facultyNames[$i] . '</td>';
                    echo '<td>' . $facultyRanks[$i] . '</td>';
                    echo '<td>' . $facultyTypes[$i] . '</td>';
                    echo '</tr>';
                }
                ?>

            </table>

            <br><br>

        </div>


    </div>




    </div>

    <script src="../plugins/js/nav.js"></script>
</body>

</html>
