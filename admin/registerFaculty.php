<?php
include '../connection.php';
session_start();


if (!isset($_SESSION['uId']) || empty($_SESSION['uId'])) {
    header("Location: ../index.php");
    exit;
}

if (isset($_POST["getData"])) {
    $userId = !empty($_POST["userId"]) ? $_POST["userId"] : 0;

    $sqlUsers = "SELECT F_Name, L_Name, User_DOB, User_SSN, User_Type FROM `users` WHERE `User_ID` = $userId";

    $queryUsers = mysqli_query($conn, $sqlUsers);

    $result = [
        'error' => '',
        'success' => true,
        'users' => [],
        'login_info' => [],
        'part' => []
    ];


    if (mysqli_num_rows($queryUsers) == 0) {
        $result['success'] = false;
        $result['error'] = "There is no Users data";
    } else {
        $result['users'] = mysqli_fetch_assoc($queryUsers);

        $sqlLoginInfo = "SELECT User_ID, User_Email, User_Password, User_Type FROM `login_info` WHERE `User_ID` = $userId";

        $queryLoginInfo = mysqli_query($conn, $sqlLoginInfo);

        if (mysqli_num_rows($queryLoginInfo) == 0) {
            $result['success'] = false;
            $result['error'] = "There is no Login Info data";
        } else {
            $result['login_info'] = mysqli_fetch_assoc($queryLoginInfo);
        }
    }

    echo json_encode($result);
    exit;

}


if (isset($_POST["validEmail"])) {
    $email = $_POST['email'];

    $sql = "SELECT * FROM `login_info` WHERE `User_Email` = '" . $email . "' AND User_Type = 'Faculty'";

    $query = mysqli_query($conn,$sql);
    $row = mysqli_fetch_assoc($query);

    $result = [
            'error' => '',
        'success' => true
    ];

    if(mysqli_num_rows($query) > 0){
        $result['error'] = "This email exists...";
        $result['success'] = false;
    }

    echo json_encode($result);
    exit;
}


$faculty_added = false;
if(isset($_POST["submit"])){

    $userId = !empty($_POST["userId"]) ? $_POST["userId"] : 0;

    /////user
    $facultyFname = $_POST["facultyFname"];
    $facultyLname = $_POST["facultyLname"];
    $facultyDob = $_POST["facultyDob"];
    $facultySsn = $_POST["facultySsn"];

    //login
    $facultyEmail = $_POST["facultyEmail"];
    $facultyPassword = $_POST["facultyPassword"];

    //faculty
    $facultyRank = $_POST["facultyRank"];
    $facultyFullpart = $_POST["fullPart"];


    //4th
    $numsec = $_POST["numsec"];
    $seclim = $_POST["seclim"];

    if ($userId == 0) {

        $sql2 = "INSERT INTO users (F_Name, L_Name, User_DOB, User_SSN, User_Type) VALUES ('".$facultyFname."','".$facultyLname."','".$facultyDob."','".$facultySsn."','Faculty' )";

        if ($conn->query($sql2) === TRUE) {
            $facultyUid = $conn->insert_id;
            $facultyID = $facultyUid;
            $sql3 = "INSERT INTO login_info (User_ID, User_Email, User_Password, User_Type) VALUES ('".$facultyUid."','".$facultyEmail."','".$facultyPassword."','Faculty' )";

            if ($conn->query($sql3) === TRUE) {

                $sql4 = "INSERT INTO faculty (`Faculty_ID`, `Faculty_Rank`, `Faculty_Type`) VALUES ('".$facultyID."', '".$facultyRank."','".$facultyFullpart."')";

                if ($conn->query($sql4) === TRUE) {
                    $sql5 = '';
                    if($facultyFullpart == 'Part_T_Faculty'){
                        $sql5 = "INSERT INTO Part_T_Faculty (`Faculty_ID`, `Num_Sec_Taught`, `Section_Limit`) VALUES ('".$facultyID."','".$numsec."','".$seclim."')";
                    }else{
                        $sql5 = "INSERT INTO Full_T_Faculty (`Faculty_ID`, `Num_Sec_Taught`, `Section_Limit`) VALUES ('".$facultyID."','".$numsec."','".$seclim."')";
                    }

                    if ($conn->query($sql5) === TRUE) {
                    } else {
                        // header('location:faculty.php');
                        echo '<script>alert("Faculty Added Successfully")</script>';
                        echo "Error: " . $sql5 . "<br>" . $conn->error;
                    }
                } else {
                    echo "Error: " . $sql4 . "<br>" . $conn->error;
                }
            } else {
                echo "Error: " . $sql3 . "<br>" . $conn->error;
            }
        } else {
            echo "Error: " . $sql2 . "<br>" . $conn->error;
        }
    } else {
        if($facultyFullpart == 'Part_T_Faculty'){
            $sql5 = "UPDATE `Part_T_Faculty` SET `Num_Sec_Taught` = '$numsec', `Section_Limit` = '$seclim' WHERE `Faculty_ID` = " . $userId;
        }else{
            $sql5 = "UPDATE `Full_T_Faculty` SET `Num_Sec_Taught` = '$userId', `Section_Limit` = '$numsec' WHERE `Faculty_ID` = " . $userId;
        }

        $sql7 = "UPDATE users SET F_Name = '".$facultyFname."', L_Name = '".$facultyLname."', User_DOB='".$facultyDob."', User_SSN = '".$facultyDob."', User_Type = 'Faculty' WHERE User_ID = '".$userId."'";
        $sql8 = "UPDATE login_info SET User_Email = '".$facultyEmail."', User_Password = '".$facultyPassword."', User_Type='Faculty' WHERE User_ID = '".$userId."'";

        if ($conn->query($sql5) === TRUE) {
            if ($conn->query($sql7) === TRUE) {

                if ($conn->query($sql8) === TRUE) {
                } else {
                    echo "Error: " . $sql8 . "<br>" . $conn->error;
                }

            } else {
                echo "Error: " . $sql7 . "<br>" . $conn->error;
            }

        } else {
            echo "Error: " . $sql5 . "<br>" . $conn->error;
        }
    }

}



if(isset($_POST["edit"])){

    /////user
    $facultyUid = $_POST["facultyUid"];
    $facultyFname = $_POST["facultyFname"];
    $facultyLname = $_POST["facultyLname"];
    $facultyDob = $_POST["facultyDob"];
    $facultySsn = $_POST["facultySsn"];

    //login
    $facultyEmail = $_POST["facultyEmail"];
    $facultyPassword = $_POST["facultyPassword"];

    //faculty
    $facultyID = $_POST["facultyID"];
    $facultyRank = $_POST["facultyRank"];
    $facultyFullpart = $_POST["fullPart"];


    //4th
    $numsec = $_POST["numsec"];
    $seclim = $_POST["seclim"];



}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Island - Add Student</title>

    <link rel="stylesheet" href="../plugins/css/index.css">
    <link rel="stylesheet" href="../plugins/css/navbar.css">
    <link rel="stylesheet" href="../plugins/css/admin.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <style>
        .disable {
            pointer-events: none;
            opacity: 0.6;
        }
    </style>

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

        <div class="sidenav">
            <aside class="col-xs-12 col-md-12 col-lg-12 side-menu">
                <nav class="left-nav hidden-xs hidden-sm hidden-md">
                    <ul class="nolist">
                        <li class="nolistHighlight image-container">
                            <a href="profile.php">Hello Admin <?php echo $_SESSION["uemail"] ?></a>
                        </li>
                        <li>
                            <a href="#">Register A User</a>
                            <ul class="nolist">
                                <li>
                                    <a href="registerStudent.php">Student</a>
                                </li>
                                <li>
                                    <a href="registerFaculty.php">Faculty</a>
                                </li>
                                <li>
                                    <a href="registerResearcher.php">Researcher</a>
                                </li>
                              
                            </ul>
                        </li>
                        <li>
                            <a href="#"> Department </a>
                            <ul class="nolist">
                                <li>
                                    <a href="addDepartment.php">Add</a>
                                </li>
                                <li>
                                    <a href="editDepartment.php">Edit</a>
                                </li>
                                <li>
                                    <a href="departments.php">Delete</a>
                                </li>
                                
                            </ul>
                        </li>
                        <li>
                            <a href="#/">Course</a>
                            <ul class="nolist">
                                <li>
                                    <a href="addCourse.php">Add</a>
                                </li>
                                <li>
                                    <a href="editCourse.php">Edit</a>
                                </li>
                                <li>
                                    <a href="courses.php">Delete</a>
                                </li>
                                
                            </ul>
                        </li>
                        <li>
                            <a href="#">Student</a>
                            <ul class="nolist">
                                <li>
                                    <a href="adminViewStudent.php">View Student</a>
                                </li>
                                <li>
                                    <a href="adminEditStudent.php">Edit Student</a>
                                </li>
                                <li>
                                    <a href="adminViewStudentCourse.php">View Courses</a>
                                </li>
                                <li>
                                    <a href="adminAddStudentCourse.php">Add Course</a>
                                </li>
                                <li>
                                    <a href="adminViewStudentMajor.php">View Student Major</a>
                                </li>
                                <li>
                                    <a href="adminAddStudentMajor.php">Add Student Major</a>
                                </li>
                                <li>
                                    <a href="viewStudentMinor.php">View Student Minor</a>
                                </li>
                                <li>
                                    <a href="adminAddStudentMinor.php">Add Student Minor</a>
                                </li>
                                <li>
                                    <a href="adminRemoveStudentMinor.php">Remove Student Minor</a>
                                </li>
                                <li>
                                    <a href="adminAddHold.php">Add Hold</a>
                                </li>
                                <li>
                                    <a href="removeHold.php">Remove Hold</a>
                                </li>
                                <li>
                                    <a href="adminViewStudentHold.php">View Student Hold</a>
                                </li>
                                <li>
                                    <a href="adminAddStudentHold.php">Add Student Hold</a>
                                </li>
                                <li>
                                    <a href="adminRemoveStudentHold.php">Remove Student Hold</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Time Slots</a>
                            <ul class="nolist">
                                <li>
                                    <a href="dueDates.php">Due Dates</a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="#">Faculty</a>
                            <ul class="nolist">
                                <li>
                                    <a href="adminViewFacultyDepartment.php">View Faculty Departments</a>
                                </li>
                                <li>
                                    <a href="adminAddFacultyDepartment.php">Add Faculty Departments</a>
                                </li>
                                <!-- <li>
                                    <a href="adminFacultyStudentHistory.php">View Student History</a>
                                </li> -->
                                <li>
                                    <a href="adminFacultyresetPassword.php">Reset Password</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </aside>
        </div>
        <div class="main-page">
            Select status: <select id="selectStatus" onchange="onChangeStatus(event)">
                <option value="add">Add</option>
                <option value="edit">Edit</option>
            </select>
            <br/>
            <div id="user_id_wrapper" style="display: none">
                Insert User ID to change: <input type="number" name="user_id" onblur="onGetDataFromID(event)">
            </div>
            <br/>
            <h3 id="add-student-section"> <span id="changeTitle">Add</span> Faculty </h3>
            <br>
            <?php
            if ($faculty_added) {
                echo "Student Added Successfully";
            }
            ?>
<form method="post" action="" id="registerForm">
    <input type="hidden" name="userId" id="userId">
        First Name: &nbsp;<input type="text" placeholder="Faculty First Name" id="facultyFname" name="facultyFname" required/><br><br>
        Last Name: &nbsp;<input type="text" placeholder="Faculty Last Name" id="facultyLname" name="facultyLname" required/><br><br>
        Birthday: &nbsp;<input type="date" placeholder="Faculty DOB" id="facultyDob" name="facultyDob" required/><br><br>
        Faculty SSN: &nbsp;<input type="text" placeholder="Faculty SSN" id="facultySsn" name="facultySsn" required/><br><br>
        Email: &nbsp;<input type="email" onblur="onValidEmail(event)" id="facultyEmail" placeholder="Faculty Email" name="facultyEmail" required/><br><br>
        Password: &nbsp;<input type="password" onblur="onConfirmPassword(event)" id="facultyPassword" placeholder="Faculty Password" id="facultyPassword" name="facultyPassword" /><br><br>
        Confirm Password: &nbsp;<input type="password" onblur="onConfirmPassword(event)" id="confirmPassword" placeholder="Confirm Password" id="confirmPassword" name="confirmPassword" /><br><br>
        Section Limit: &nbsp;<input type="text" placeholder="Faculty Rank" name="facultyRank" id="facultyRank" /><br><br>
        Number Sections: &nbsp;<input type="text" placeholder="Number sections" name="numsec" id="numsec" /><br><br>
        Section Limit: &nbsp;<input type="text" placeholder="Section Limit" name="seclim" id="seclim" /><br><br>
        <select name="fullPart">
            <option value="Part_T_Faculty">Part time</option>
            <option value="Full_T_Faculty">Full time</option>
        </select>
        
        <br><br>
        <input type="submit" class="btn btn-info" name="submit" style="width: 100px;" value="Save"/>
        <br><br>
    </form>

        </div>
    </div>
    <script>
        function onValidEmail(e) {
            e.preventDefault();

            let email = e.target.value;
            let data = {
                'email' : email,
                'validEmail' : true
            }

            let vm = this;

            $.ajax({
                method: "POST",
                url: window.location.href,
                data: data,
                dataType: 'json',
                success: function (res) {
                    if (res !== undefined) {
                        if (res.success !== true) {
                            alert(res.error);
                            $('#facultyEmail').val("");
                        }
                    }
                },
                complete: function () {
                }
            });
        }

        function onConfirmPassword(e) {
            e.preventDefault();
            let facultyPassword = $('#facultyPassword').val();
            let confirmPassword = $('#confirmPassword').val();

            if (confirmPassword == "" || confirmPassword == undefined
                || facultyPassword == "" || facultyPassword == undefined) {

                return;
            }

            if (confirmPassword !== facultyPassword) {
                alert('please check and match password!');
                e.target.value = "";
            }
        }

        function onChangeStatus(e) {
            e.preventDefault();

            if (e.target.value == 'add') {
                $('#user_id_wrapper').hide();
                $('#registerForm').removeClass('disable');
                $('#changeTitle').html('Add');
                $('#user_id_wrapper input').val('');
                $('#user_id').val('0');
            } else {
                $('#user_id_wrapper').show();
                $('#registerForm').addClass('disable');
                $('#changeTitle').html('Edit');
            }
        }

        function onGetDataFromID(e) {
            e.preventDefault();

            let userId = e.target.value;

            if (userId == "" || userId == undefined) {
                return;
            }

            let data = {
                userId,
                getData: true
            }

            $('#registerForm').addClass('disable');
            $.ajax({
                method: "POST",
                url: window.location.href,
                data: data,
                dataType: 'json',
                success: function (res) {
                    if (res !== undefined) {
                        if (res.success == true) {
                            $('#registerForm').removeClass('disable');

                            $('#userId').val(res.login_info.User_ID);

                            if (res.users != [] || res.users != {}) {
                                $('#facultyFname').val(res.users.F_Name);
                                $('#facultyLname').val(res.users.L_Name);

                                let dob = res.users.User_DOB;
                                let newDob = '';
                                if (dob !== "" || dob !== undefined) {
                                    let dobArr = dob.split('/');
                                    if (dobArr[0] < 10) {
                                        dobArr[0] = "0" + dobArr[0];
                                    }

                                    if (dobArr[1] < 10) {
                                        dobArr[1] = "0" + dobArr[1];
                                    }
                                    newDob = dobArr[2] + '-' + dobArr[0] + '-' + dobArr[1];
                                }

                                $('#facultyDob').val(newDob);
                                $('#facultySsn').val(res.users.User_SSN);
                            }

                            if (res.login_info != [] || res.login_info != {}) {
                                $('#facultyEmail').val(res.login_info.User_Email);
                                $('#facultyPassword').val(res.login_info.User_Password);
                                $('#confirmPassword').val(res.login_info.User_Password);

                            }
                        } else {
                            alert(res.error);
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