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
    $sqlResearcher = "SELECT researcher.*, users.first_name, users.last_name, users.dob, users.email, users.password, users.ssn";
    $sqlResearcher .= " FROM `researcher` JOIN `users` ON `users`.`id` = `researcher`.`user_id`";

    if(!empty($search_text)) {
        $sqlResearcher .= " WHERE users.first_name LIKE '%$search_text%' OR users.email LIKE '%$search_text%'";
        $sqlResearcher .= " OR users.last_name LIKE '%$search_text%' OR researcher.id LIKE '%$search_text%'";
    }

    $totalQuery = mysqli_query($conn, $sqlResearcher);
    $total_count = mysqli_num_rows($totalQuery);

    $sqlResearcher .= " LIMIT $page_size OFFSET $start_number";

    $query = mysqli_query($conn, $sqlResearcher);


    $count = 0;
    $resultHtml = "";
    while($row = mysqli_fetch_assoc($query)){
        $resultHtml .= '<tr id="row_' . $row["id"] . '">';
        $resultHtml .= '<td>'.$row["id"].'</td>';
        $resultHtml .= '<td>'.$row["first_name"] . " " . $row["last_name"] . '</td>';
        $resultHtml .= '<td>'.$row["dob"].'</td>';
        $resultHtml .= '<td>'.$row["email"].'</td>';
        $resultHtml .= '<td>'.$row["password"].'</td>';
        $resultHtml .= '<td>'.$row["ssn"].'</td>';
        $resultHtml .= '<td><button type="button" class="btn btn-sm btn-success" onclick="onEditRow(' . $row["id"] . ')" title="Edit"><i class="fa fa-edit"></i> </button> 
            <button type="button" class="btn btn-sm btn-danger" onclick="onDeleteRow(' . $row["id"] . ')" title="Delete"><i class="fa fa-trash"></i> </button> </td>';
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
        $sql  = "DELETE r.*, u.* FROM researcher as r JOIN users as u ON r.user_id = u.id WHERE r.id = '".$delete_id."'";
        if ($conn->query($sql)) {
            $ret['success'] = true;
        }
    }

    echo json_encode($ret);
    exit;
}

if (isset($_POST['save_row'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $ssn = $_POST['ssn'];
    $type = 'Researcher';

    $id = $_POST['id'];
    $user_id = $_POST['user_id'];

    $ret = [
        'success' => false,
        'message' => ''
    ];

    //    check
    $sqlCheck = "SELECT * FROM users WHERE email = '$email' AND `type` = '$type'";

    if (!empty($user_id)) {
        $sqlCheck .= " AND id <> $user_id";
    }
    $checkQuery = mysqli_query($conn, $sqlCheck);

    if (mysqli_num_rows($checkQuery) > 0){
        $ret['message'] = "Email already exists!";

        echo json_encode($ret);
        exit;
    }

    if(empty($id)) {
        //    add to users
        $sql = "INSERT INTO users (`first_name`, `last_name`, `dob`, `email`, `password`, `ssn`, `type`) VALUES ('$first_name', '$last_name', '$dob', '$email', '$password', '$ssn', '$type')";

        if ($conn->query($sql)) {
            $user_id = $conn->insert_id;

//        add to faculty table
            $sql = "INSERT INTO researcher (user_id) VALUES ('$user_id')";

            if ($conn->query($sql)) {
                $ret['success'] = true;
            } else {
                $ret['success'] = false;
                $ret['message'] = "Error in student";
            }
        } else {
            $ret['message'] = "Error in users";
        }
    } else {
        // update
        $user_id = $_POST['user_id'];

        $sql = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', dob = '$dob', email = '$email', password = '$password', ssn = '$ssn'";
        $sql .= " WHERE id = $user_id";

        if ($conn->query($sql)) {
            $ret['success'] = true;
        } else {
            $ret['message'] = "Error in User";
        }
    }

    echo json_encode($ret);
    exit;
}

if (isset($_POST['get_row'])) {
    $edit_id = $_POST['edit_id'];
    $sqlResearcher = "SELECT researcher.*, users.first_name, users.last_name, users.dob, users.email, users.password, users.ssn";
    $sqlResearcher .= " FROM `researcher` JOIN `users` ON `users`.`id` = `researcher`.`user_id`";
    $sqlResearcher .= " WHERE researcher.id = '$edit_id' LIMIT 1";

    $query = mysqli_query($conn, $sqlResearcher);
    $row = mysqli_fetch_assoc($query);

    echo json_encode($row);
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
                <h3>Researchers</h3>
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
                <th width="150px">Action</th>
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
<div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="addModal" aria-hidden="true" data-backdrop="false" style="background: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form method="post" id="form-add" onsubmit="return onSave(event)">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row py-1">
                        <h3 class="text-center " style="margin: auto; color: red" id="add-modal-title">Add Row</h3>
                    </div>
                    <div class="row">
                        <input type="hidden" class="form-control" id="id" name="id" required/>
                        <input type="hidden" class="form-control" id="user_id" name="user_id" required/>

                        <div class="col-sm-6 py-1">
                            <input type="text" class="form-control" placeholder="First Name" id="first_name" name="first_name" required/>
                        </div>
                        <div class="col-sm-6 py-1">
                            <input type="text" class="form-control" placeholder="Last Name" id="last_name" name="last_name" required/>
                        </div>
                        <div class="col-sm-6 py-1">
                            <input type="date" class="form-control" placeholder="DOB" id="dob" name="dob" required/>
                        </div>
                        <div class="col-sm-6 py-1">
                            <input type="text" class="form-control" placeholder="SSN" id="ssn" name="ssn" required/>
                        </div>
                        <div class="col-sm-6 py-1">
                            <input type="email" class="form-control" placeholder="Email" id="email" name="email" required/>
                        </div>
                        <div class="col-sm-6 py-1">
                            <input type="text" class="form-control" placeholder="Password" id="password" name="password" required/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" id="btn-cancel">Cancel</button>
                    <button type="submit" class="btn btn-success" id="btn-save">Save</button>
                </div>
            </form>
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
<script src="../js/administrator/researchers.js"></script>
</body>
</html>
