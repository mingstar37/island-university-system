<?php
session_start();
if (empty($_SESSION['user_id']) || empty($_SESSION['user_email']) || empty($_SESSION['type'])) {
    header('location: ../index.php');
    exit;
}

include '../connection.php';

$sqlStudent = "SELECT student.*, users.first_name, users.last_name, users.dob, users.email, users.password, users.ssn, student.student_gpa, student.student_type";
$sqlStudent .= " FROM `student` INNER JOIN `users` ON `users`.`id` = `student`.`user_id`";

$queryStudent = mysqli_query($conn, $sqlStudent);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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
            <div class="row">
                <div class="col-lg-8">
                    <h3>Students</h3>
                </div>
                <div class="col-lg-4 text-right">
                    <button type="button" class="btn btn-sm btn-success" onclick="onAddNew()">
                        <i class="fa fa-plus"></i> &nbsp;Add New
                    </button>
                </div>
            </div>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>DOB</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>SSN</th>
                    <th>GPA</th>
                    <th>Type</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody id="table-body">
                <?php
                $count = 0;
                while($rowStudent = mysqli_fetch_assoc($queryStudent)){
                    echo '<tr>';
                    echo '<td>'.$rowStudent["id"].'</td>';
                    echo '<td>'.$rowStudent["first_name"] . " " . $rowStudent["last_name"] . '</td>';
                    echo '<td>'.$rowStudent["dob"].'</td>';
                    echo '<td>'.$rowStudent["email"].'</td>';
                    echo '<td>'.$rowStudent["password"].'</td>';
                    echo '<td>'.$rowStudent["student_gpa"].'</td>';
                    echo '<td>'.$rowStudent["student_type"].'</td>';
                    echo '<td></td>';
                    echo '</tr>';

                    $count ++;
                }

                if ($count < 1) {
                    echo '<tr>
                        <td colspan="10">There is no data</td></tr>';
                }
                ?>
                </tbody>
                <tfoot>

                </tfoot>
            </table>
            <div class="pagination-wrapper text-center" id="pagination-wrapper">
<!--                <ul class="pagination pagination-sm">-->
<!--                    <li class="page-item" style="width: 80px"><a class="page-link" href="#">Previous</a></li>-->
<!--                    <li class="page-item"><a class="page-link" href="#">1</a></li>-->
<!--                    <li class="page-item active"><a class="page-link" href="#">2</a></li>-->
<!--                    <li class="page-item"><a class="page-link" href="#">3</a></li>-->
<!--                    <li class="page-item" style="width: 80px"><a class="page-link" href="#">Next</a></li>-->
<!--                </ul>-->
            </div>
        </div>
    </div>
    <div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="addModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <script src="../plugins/js/nav.js"></script>
    <script src="../js/administrator/student.js"></script>
</body>
</html>
