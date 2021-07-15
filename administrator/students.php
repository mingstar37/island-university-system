<?php
session_start();
if (empty($_SESSION['user_id']) || empty($_SESSION['user_email']) || empty($_SESSION['type'])) {
    header('location: ../index.php');
    exit;
}

include '../connection.php';

if (isset($_POST['load_data'])) {

    $search_text = $_POST['search_text'];
    $start_number = $_POST['start_number'];
    $page_size = $_POST['page_size'];
    $sqlStudent = "SELECT student.*, users.first_name, users.last_name, users.dob, users.email, users.password, users.ssn, student.student_gpa, student.student_type";
    $sqlStudent .= " FROM `student` LEFT JOIN `users` ON `users`.`id` = `student`.`user_id`";

    if(!empty($search_text)) {
        $sqlStudent .= " WHERE users.first_name LIKE '%$search_text%' OR users.email LIKE '%$search_text%'";
        $sqlStudent .= " OR users.last_name LIKE '%$search_text%' OR student.id LIKE '%$search_text%'";
        $sqlStudent .= " OR student.student_gpa LIKE '%$search_text%' OR student.student_type LIKE '%$search_text%'";
    }

    $totalQuery = mysqli_query($conn, $sqlStudent);
    $total_count = mysqli_num_rows($totalQuery);

    $sqlStudent .= " LIMIT $page_size OFFSET $start_number";

    $queryStudent = mysqli_query($conn, $sqlStudent);


    $count = 0;
    $resultHtml = "";
    while($rowStudent = mysqli_fetch_assoc($queryStudent)){
        $resultHtml .= '<tr id="row_' . $rowStudent["id"] . '">';
        $resultHtml .= '<td>'.$rowStudent["id"].'</td>';
        $resultHtml .= '<td>'.$rowStudent["first_name"] . " " . $rowStudent["last_name"] . '</td>';
        $resultHtml .= '<td>'.$rowStudent["dob"].'</td>';
        $resultHtml .= '<td>'.$rowStudent["email"].'</td>';
        $resultHtml .= '<td>'.$rowStudent["password"].'</td>';
        $resultHtml .= '<td>'.$rowStudent["ssn"].'</td>';
        $resultHtml .= '<td>'.$rowStudent["student_gpa"].'</td>';
        $resultHtml .= '<td>'.$rowStudent["student_type"].'</td>';
        $resultHtml .= '<td><button type="button" class="btn btn-sm btn-success" onclick="onEditRow(' . $rowStudent["id"] . ')" title="Edit"><i class="fa fa-edit"></i> </button> 
            <button type="button" class="btn btn-sm btn-danger" onclick="onDeleteRow(' . $rowStudent["id"] . ')" title="Delete"><i class="fa fa-trash"></i> </button> </td>';
        $resultHtml .= '</tr>';

        $count ++;
    }

    if ($count < 1) {
        $resultHtml .= '<tr>
                            <td colspan="10">There is no data</td></tr>';
    }

    $ret = [
            'html' => $resultHtml,
        'total_count' => $total_count
    ];

    echo json_encode($ret);
    exit;
}

if (isset($_POST['delete_row'])) {
    $delete_id = $_POST['delete_id'];

    $ret = [
            'success' => false
    ];

    if (!empty($delete_id)) {
        $sql  = "DELETE s.*, u.* FROM student as s LEFT JOIN users as u ON s.user_id = u.id WHERE s.id = '".$delete_id."'";
        if ($conn->query($sql)) {
            $ret['success'] = true;
        }
    }

    echo json_encode($ret);
    exit;
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
    <link rel="stylesheet" href="../plugins/css/toastr.css">
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
            <div class="row table-toolbar">
                <div class="col-lg-6">
                    <h3>Students</h3>
                </div>
                <div class="col-lg-6 text-right" style="display: flex; justify-content: flex-end">
                    <div class="input-group search-input" style="max-width: 300px; margin-right: 20px">
                        <input type="text" id="search-text" class="form-control" placeholder="search" onkeyup="onSearchKeyup(event)">
                        <div class="input-group-btn">
                            <button class="btn btn-default" type="button" onclick="onSearch()">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
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

                </tbody>
                <tfoot>

                </tfoot>
            </table>
            <div class="pagination-wrapper text-center" id="pagination-wrapper">
                <ul class="pagination pagination-sm">
                    <li class="page-item" style="width: 80px; background-color: #86cfda"><a class="page-link" id="pagination-prev" href="javascript:void(0)">Previous</a></li>
                    <li class="page-item active"><a class="page-link" href="javascript:void(0)" id="pagination-one">1</a></li>
                    <li class="page-item"><a class="page-link" href="javascript:void(0)" id="pagination-two">2</a></li>
                    <li class="page-item"><a class="page-link" href="javascript:void(0)" id="pagination-three">3</a></li>
                    <li class="page-item" style="width: 80px"><a class="page-link" href="javascript:void(0)" id="pagination-next">Next</a></li>
                    <li class="page-item">
                        <select id="page-select" style="width: 60px; height: 100%" onchange="onChangePageNumber(event)">
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </li>
                </ul>
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
    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3 style="color: red;" class="text-center">Do you want to delete?</h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-success" onclick="onDelete()">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../plugins/js/toastr.js"></script>
    <script src="../plugins/js/nav.js"></script>
    <script src="../js/administrator/student.js"></script>
</body>
</html>
