<?php
include '../connection.php';
session_start();

$fullPart = "Full Time";
$advisors = array();
    $faculties = array();


$faculty_fetched = false;


if(isset($_POST["submit"])){
    $sId = $_POST["facultyId"];

  

    $sql = "SELECT * FROM users where User_ID = '".$sId."'";
    $query = mysqli_query($conn,$sql);
    $rowUser = mysqli_fetch_assoc($query);

    $sqlType = "SELECT * FROM faculty where Faculty_ID = '".$sId."'";
    $queryType = mysqli_query($conn,$sqlType);
    $rowType = mysqli_fetch_assoc($queryType);

    // courses
    $sqlSection = "SELECT * FROM section WHERE Faculty_ID = '".$sId."'";
    $querySection = mysqli_query($conn,$sqlSection);

    // department
    $sqlDept = "SELECT * FROM dept_faculty WHERE Faculty_ID = '".$sId."'";
    $queryDept = mysqli_query($conn,$sqlDept);
    $rowDept = mysqli_fetch_assoc($queryDept);

    $deptId = $rowDept["Dept_ID"];
    $sqlD = "SELECT * FROM department WHERE Dept_ID = '".$deptId."'";
    $queryD = mysqli_query($conn,$sqlD);
    $rowD = mysqli_fetch_assoc($queryD);


    $faculty_fetched = true;
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
        <!-- <h1 id="banner"> Island University - Admin </h1> -->

        <?php
            include 'sidenav.php';
        ?>
        <div class="main-page">


            <h3 id="add-student-section"> View Faculty </h3>
            <br>
           <form method="post" action="">
                Enter Faculty ID: <input type="text" name="facultyId" placeholder="Faculty Id" /> <br><br>
                <input type="submit" class="btn btn-primary" name="submit" value="View Faculty" />
        <form>

        <br>
        <hr>
        <br>
        <?php
            if($faculty_fetched == true){
                ?>
                <br>
                Name: <span style="font-weight:bold;font-size:20px;"><?php echo $rowUser["F_Name"].' '.$rowUser["L_Name"] ?></span> <br>
                Faculty Type: <span style="font-weight:bold;font-size:20px;"><?php  echo $rowType["Faculty_Type"] ?></span> <br>
                Faculty Rank: <span style="font-weight:bold;font-size:20px;"><?php  echo $rowType["Faculty_Rank"] ?></span> <br>

                <br>
                <br>
                <h3>Courses</h3>
                <table class="view-table">
                <tr>
                    <th>Course ID</th>
                    <th>Building Name</th>
                    <th>Semester</th>
                    <th>Section</th>
                </tr>
            <?php
                while($rowSection = mysqli_fetch_assoc($querySection)){
                    echo '<tr>';
                    echo '<td>'.$rowSection["Course_ID"].'</td>';
                    echo '<td>'.$rowSection["Building_Name"].'</td>';
                    echo '<td>'.$rowSection["Sem_Term_Year"].'</td>';
                    echo '<td>'.$rowSection["Section"].'</td>';
                    echo '</tr>';
                }
                   
            ?>

        </table>
        <br><br>

                <h3>Department</h3>
                <p><?php   echo $rowD["Dept_Name"] ?></p>

     

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