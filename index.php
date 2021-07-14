<?php
session_start();
include 'connection.php';

$student_added = false;

if (isset($_POST["confirmEmail"])) {
    $result = [
        'error' => '',
        'success' => false,
    ];


    $email = $_POST['email'];
    $type = $_POST['type'];

    $randomNumber = rand(10000, 99999);

    $sql = "SELECT * FROM `users` WHERE `email` = '" . $email."' AND `type` = '" . $type . "'";
    $query = mysqli_query($conn,$sql);
    $row = mysqli_fetch_assoc($query);

    if(mysqli_num_rows($query) == 0){
        $result['error'] = "That email doesn't exist.";
        $result['success'] = false;
    } else {
        $sql = "UPDATE `users` SET `confirm_number` = " . $randomNumber . " WHERE `id` = " . $row['id'];

        if ($conn->query($sql) === true) {
            // send email to user
            $result['success'] = true;
        } else {
            $result['success'] = false;
            $result['error'] = 'Database issue!';
        }
    }

    echo json_encode($result);
    exit;
}

if (isset($_POST["confirmNumber"])) {
    $result = [
        'error' => '',
        'success' => false,
    ];

    $email = $_POST['email'];
    $number = $_POST['number'];
    $type = $_POST["type"];

    $sql = "SELECT * FROM `users` WHERE `email` = '" . $email."' AND `confirm_number` = " . $number . " AND `type` = '" . $type ."'";
    $query = mysqli_query($conn,$sql);
    $row = mysqli_fetch_assoc($query);

    if(mysqli_num_rows($query) == 0){
        $result['error'] = "That number is not correct.";
        $result['success'] = false;
    } else {
        $result['success'] = true;
    }

    echo json_encode($result);
    exit;
}

if (isset($_POST["confirmPassword"])) {
    $result = [
        'error' => '',
        'success' => false,
    ];

    $email = $_POST['email'];
    $number = $_POST['number'];
    $password = $_POST['newPassword'];
    $type = $_POST['type'];

    $sql = "UPDATE `users` SET `password` = '" . $password . "' WHERE `email` = '" . $email. "' AND `confirm_number` = " . $number . " AND `type` = '" . $type . "'";

    if ($conn->query($sql) == true) {
        $result['success'] = true;
    } else {
        $result['error'] = "The password was not changed.";
        $result['success'] = false;
    }

    echo json_encode($result);
    exit;
}

if(isset($_POST["login"])){
    $userEmail = $_POST["email"];
    $password = $_POST["password"];
    $userType = $_POST["type"];

    $sql = "";
    $sql = "SELECT * FROM `users` WHERE `email` = '" . $userEmail."' and  `password` = '" . $password."' AND `type` = '" . $userType . "'";
    $query = mysqli_query($conn,$sql);
    $row = mysqli_fetch_assoc($query);

    $result = [
        'error' => '',
        'success' => false,
        'type' => ''
    ];

    if(mysqli_num_rows($query) == 0){
        $result['error'] = "The login credentials entered are invalid";
    }else{
        $_SESSION["type"] = $row["type"];
        $_SESSION["user_email"] = $row['email'];
        $_SESSION["user_id"] = $row['id'];

        $result['success'] = true;
        $result['type'] = $row["type"];
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
    <title>Island University</title>

    <link rel="stylesheet" href="./plugins/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/css/index.css">
    <link rel="stylesheet" href="plugins/css/navbar.css">
    <link rel="stylesheet" href="./plugins/css/toastr.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="./plugins/js/toastr.js"></script>
</head>
<body>
    <div id="header">
        <div id="upper-header">
            <div class="search-box">
                <!-- <input type="text" placeholder="Search"/> -->
                <!-- <button>Search</button> -->
            </div>
        </div>
        <div id="lower-header">
            <a href="index.php"><div class="navi"> Home </div></a>
            <a href="admission.php"><div class="navi"> Admission </div></a>
            <a href="administrator.php"><div class="navi"> Administrator </div></a>
            <a href="academics.php"><div class="navi"> Academics </div></a>
            <a href="courseCatalog.php"><div class="navi"> Course Catalog </div></a>
            <a href="contact.php"><div class="navi"> Contact Us </div></a>
        </div>
    </div>

    <div id="resetPasswordModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">

                    <div class="carousel slide" data-ride="carousel" data-interval="false">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <form onsubmit="return onSendConfirmEmail(event)" style="min-height: 195px">
                                    <div class="form-group">
                                        <h3 style="text-align: center; padding-bottom: 20px;">Please enter email.</h3>
                                        <div class="row align-items-center">
                                            <div class="col-9" style="padding: 20px">
                                                <input type="email" id="confirm_email" placeholder="Confirm email..." class="form-control" required/>
                                                <br/>
                                                <select name="type" id="confirm_type">
                                                    <option value="Administrator">Administrator</option>
                                                    <option value="Faculty">Faculty</option>
                                                    <option value="Student">Student</option>
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <button class="btn btn-sm btn-outline-danger" id="btn_confirm_email" style="width: 80%;" type="submit">Send</button>
                                            </div>
                                        </div>
                                        <div style="height: 30px">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="carousel-item">
                                <form onsubmit="return onSendConfirmNumber(event)" style="min-height: 195px">
                                    <div class="form-group">
                                        <h3 style="text-align: center; padding-bottom: 20px;">Please enter confirm number.</h3>
                                        <div class="row align-items-center">
                                            <div class="col-9" style="padding: 20px">
                                                <input type="text" placeholder="confirm number" id="confirm_number" class="form-control" required/>
                                            </div>
                                            <div class="col-3">
                                                <button class="btn btn-sm btn-outline-danger" id="btn_confirm_number" style="width: 80%;" type="submit">Send</button>
                                            </div>
                                        </div>
                                        <div style="height: 30px; padding-top: 10px; text-align: right">
                                            I have not received. <a href="javascript:onResendEmail()">Resend email</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="carousel-item">
                                <form onsubmit="return onSendNewPassword(event)" style="min-height: 195px">
                                    <div class="form-group">
                                        <h3 style="text-align: center; padding-bottom: 20px;">Please enter new password.</h3>
                                        <div class="row align-items-center">
                                            <div class="col-9" style="padding: 20px">
                                                <div style="padding-bottom: 15px">
                                                    <input type="password" placeholder="new password" id="new_password" class="form-control" required/>
                                                </div>
                                                <div>
                                                    <input type="password" placeholder="confirm password" id="new_confirm_password" class="form-control" required/>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <button class="btn btn-sm btn-outline-danger" id="btn_confirm_password" style="width: 80%;" type="submit">Send</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="padding-top: 30px">
                        <div class="col-12 text-center">
                            <button type="button" class="btn btn-info" style="width: 100px" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="main-section">
        <h1 id="banner"> Island University</h1>

        <div id="calender-section">
            <div class="calender-heading">Academic Calender</div>
            <div class="calender-body">
                <div class="calender-section">
                    <a href="springCalandar.php"><span class="calender-term">Spring</span></a>
                    <table class="calender-table">

                    </table>
                </div>
                <div class="calender-section">
                <a href="fallCalandar.php"><span class="calender-term">Fall</span></a>
                    <table class="calender-table">

                    </table>
                </div>
            </div>

        </div>
        <div id="login-section">
        <form onsubmit="return onLogin(event)">
            <h3>Login</h3>
            <div class="input">
                <input type="email" id="email" name="email" onmousedown="onRemoveErrors()" placeholder="Email" class="input-box" required/>
            </div>

            <div class="input">
            <input type="password" id="password" onmousedown="onRemoveErrors()" name="password" placeholder="Password" class="input-box"/>
            </div>
            <div class="input">
                <select id="type" name="type">
                    <option value="Administrator">Administrator</option>
                    <option value="Faculty">Faculty</option>
                    <option value="Student">Student</option>

                </select>
            </div>
            <div class="error-message" id="error-message">
            </div>
            <div class="limit-time" id="limit-time">
            </div>

            <div class="input">
                <input class="btn btn-primary" type="submit" value="Sign In" name="submit"/>
            </div>

            <div class="input" style="padding-top: 20px">
                Did you forgot password? <a href="javascript:void(0)" data-toggle="modal" id="resetPassword" data-target="#resetPasswordModal">Reset password</a>
            </div>
            <!-- <div>Create an account <a href="#">register here</a></div> -->
        </form>
        </div>
    </div>
<script src="js/index.js"></script>
</body>
</html>
