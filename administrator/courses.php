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
    $sqlCourse = "SELECT c.*, pc.course_name as prereq_course_name, d.name as department_name";
    $sqlCourse .= " FROM `course` as c LEFT JOIN course as pc ON pc.id = c.prereq_course_id";
    $sqlCourse .= " LEFT JOIN department as d ON d.id = c.department_id";

    if(!empty($search_text)) {
        $sqlCourse .= " WHERE c.id LIKE '%$search_text%' OR c.department_id LIKE '%$search_text%'";
        $sqlCourse .= " OR c.prereq_course_id LIKE '%$search_text%' OR c.course_name LIKE '%$search_text%'";
        $sqlCourse .= " OR c.course_credits LIKE '%$search_text%' OR pc.course_name LIKE '%$search_text%'";
        $sqlCourse .= " OR d.name LIKE '%$search_text%'";
    }

    $totalQuery = mysqli_query($conn, $sqlCourse);
    $total_count = mysqli_num_rows($totalQuery);

    $sqlCourse .= " LIMIT $page_size OFFSET $start_number";

    $query = mysqli_query($conn, $sqlCourse);

    $count = 0;
    $resultHtml = "";
    while($row = mysqli_fetch_assoc($query)){

//        var_dump($row["prereq_course_name"]);

        $prereq_cname = empty($row["prereq_course_name"]) ? '' : $row["prereq_course_name"];
        $resultHtml .= '<tr id="row_' . $row["id"] . '">';
        $resultHtml .= '<td>'.$row["id"].'</td>';
        $resultHtml .= '<td>'.$row["prereq_course_id"] . '</td>';
        $resultHtml .= '<td>'.$row["prereq_course_name"].'</td>';
        $resultHtml .= '<td>'.$row["department_id"].'</td>';
        $resultHtml .= '<td>'.$row["department_name"].'</td>';
        $resultHtml .= '<td>'. $row["course_name"].'</td>';
        $resultHtml .= '<td>'.$row["course_credits"].'</td>';
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
        $sql  = "DELETE FROM department WHERE id = '".$delete_id."'";
        if ($conn->query($sql)) {
            $ret['success'] = true;
        }
    }

    echo json_encode($ret);
    exit;
}

if (isset($_POST['save_row'])) {
    $name = $_POST['name'];
    $dept_first_name = $_POST['dept_first_name'];
    $dept_last_name = $_POST['dept_last_name'];

    $building_name = $_POST['building_name'];
    $chair_room_no = $_POST['chair_room_no'];

    $id = $_POST['id'];

    if(empty($id)) {
        //    add to users
        $sql = "INSERT INTO department (`name`, `dept_first_name`, `dept_last_name`, `building_name`, `chair_room_no`) VALUES ('$name', '$dept_first_name', '$dept_last_name', '$building_name', '$chair_room_no')";

        if ($conn->query($sql)) {
            $user_id = $conn->insert_id;
            $ret['success'] = true;
        } else {
            $ret['message'] = "Error in department";
        }
    } else {
        // update
        $id = $_POST['id'];

        $sql = "UPDATE department SET name = '$name', dept_first_name = '$dept_first_name', dept_last_name = '$dept_last_name', building_name = '$building_name', chair_room_no = '$chair_room_no'";
        $sql .= " WHERE id = $id";

        if ($conn->query($sql)) {
            $ret['success'] = true;
        } else {
            $ret['message'] = "Error in student";
        }
    }

    echo json_encode($ret);
    exit;
}

if (isset($_POST['get_row'])) {
    $edit_id = $_POST['edit_id'];
    $sqlCourse = "SELECT *";
    $sqlCourse .= " FROM `department`";
    $sqlCourse .= " WHERE id = '$edit_id' LIMIT 1";

    $query = mysqli_query($conn, $sqlCourse);
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
    <style>
        .form-group {
            display: flex !important;
            align-items: center;
            margin-bottom: 0px !important;
        }
        .form-group label {
            margin-right: 20px;
        }
    </style>
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
                <h3>Courses</h3>
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
                <th>Prereq Course ID</th>
                <th>Prereq Course Name</th>
                <th>Department ID</th>
                <th>Department Name</th>
                <th>Course Name</th>
                <th>Course Credits</th>
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
<div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="addModal" aria-hidden="true" data-backdrop="false" style="background: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post" id="form-add" class="form-horizontal" onsubmit="return onSave(event)">
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

                        <div class="col-sm-6 py-1 form-group">
                            <label class="col-form-label" for="name">Name</label>
                            <input type="text" class="form-control" placeholder="Name" id="name" name="name" required/>
                        </div>
                        <div class="col-sm-6 py-1 form-group">
                            <label class="col-form-label" for="name">Dept First Name</label>
                            <input type="text" class="form-control" placeholder="Dept First Name" id="dept_first_name" name="dept_first_name" required/>
                        </div>
                        <div class="col-sm-6 py-1 form-group">
                            <label class="col-form-label" for="name">Dept Last Name</label>
                            <input type="text" class="form-control" placeholder="Dept Last Name" id="dept_last_name" name="dept_last_name" required/>
                        </div>
                        <div class="col-sm-6 py-1 form-group">
                            <label class="col-form-label" for="name">Building Name</label>
                            <input type="text" class="form-control" placeholder="Building Name" id="building_name" name="building_name" required/>
                        </div>
                        <div class="col-sm-6 py-1 form-group">
                            <label class="col-form-label" for="name">Chair Room No</label>
                            <input type="text" class="form-control" placeholder="Chair Room No" id="chair_room_no" name="chair_room_no" required/>
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
<script src="../js/administrator/courses.js"></script>
</body>
</html>
