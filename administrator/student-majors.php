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

    $sqlStudentMajors = "SELECT sm.*, concat(u.first_name, ' ', u.last_name) as student_name, m.major_name, m.department_id, d.name as department_name";
    $sqlStudentMajors .= " FROM `student_major` as sm";
    $sqlStudentMajors .= " LEFT JOIN `student` as s ON s.id = sm.student_id";
    $sqlStudentMajors .= " LEFT JOIN `users` as u ON u.id = s.user_id";
    $sqlStudentMajors .= " LEFT JOIN `major` as m ON m.id = sm.major_id";
    $sqlStudentMajors .= " LEFT JOIN `department` as d ON d.id = m.department_id";

    $sqlStudentMajors .= " WHERE sm.id > 0";

    $student_id = $_POST['student_id'];
    if (!empty($student_id)) {
        $sqlStudentMajors .= " AND sm.student_id = '$student_id'";
    }

    if(!empty($search_text)) {
        $sqlStudentMajors .= " AND (sm.id LIKE '%$search_text%' OR sm.major_id LIKE '%$search_text%'";
        $sqlStudentMajors .= " OR sm.type LIKE '%$search_text%' OR d.name LIKE '%$search_text%'";
        $sqlStudentMajors .= " OR u.first_name LIKE '%$search_text%' OR u.last_name LIKE '%$search_text%'";
        $sqlStudentMajors .= " OR m.major_name LIKE '%$search_text%' OR sm.student_id LIKE '%$search_text%')";
    }

    $totalQuery = mysqli_query($conn, $sqlStudentMajors);
    $total_count = mysqli_num_rows($totalQuery);

    $sqlStudentMajors .= " LIMIT $page_size OFFSET $start_number";

    $query = mysqli_query($conn, $sqlStudentMajors);

    $count = 0;
    $resultHtml = "";
    while($row = mysqli_fetch_assoc($query)){

//        var_dump($row["prereq_course_name"]);
        $resultHtml .= '<tr id="row_' . $row["id"] . '">';
        $resultHtml .= '<td>'.$row["student_id"].'</td>';
        $resultHtml .= '<td>'. $row["student_name"].'</td>';
        $resultHtml .= '<td>'. $row['major_id'] . '</td>';
        $resultHtml .= '<td>'. $row['major_name'] . '</td>';
        $resultHtml .= '<td>'.$row["department_id"].'</td>';
        $resultHtml .= '<td>'.$row["department_name"].'</td>';
        $resultHtml .= '<td>'.$row["type"].'</td>';
        $resultHtml .= '<td><button type="button" class="btn btn-sm btn-success" onclick="onEditRow(' . $row["id"] . ')" title="Edit"><i class="fa fa-edit"></i> </button> 
            <button type="button" class="btn btn-sm btn-danger" onclick="onDeleteRow(' . $row["id"] . ')" title="Delete Row"><i class="fa fa-trash"></i> </button> </td>';
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
        $sql  = "DELETE FROM advisor WHERE id = '".$delete_id."'";
        if ($conn->query($sql)) {
            $ret['success'] = true;
        }
    }

    echo json_encode($ret);
    exit;
}

if (isset($_POST['get_init_arr'])) {

//    get prereq course

    $sql = "SELECT s.id, u.first_name, u.last_name";
    $sql .= " FROM student as s INNER JOIN users as u ON u.id = s.user_id";

    $query = mysqli_query($conn, $sql);

    $student_arr = [];

    while ($row = mysqli_fetch_assoc($query)) {
        $student_arr[] = $row;
    }

    echo json_encode($student_arr);
    exit;
}

if (isset($_POST['get_student_arr'])) {

//    get prereq course

    $sql = "SELECT s.id, concat(u.first_name, ' ', u.last_name) as student_name";
    $sql .= " FROM student as s";
    $sql .= " INNER JOIN users as u ON u.id = s.user_id";
    $sql .= " GROUP BY s.id";

    $query = mysqli_query($conn, $sql);

    $student_arr = [];

    while ($row = mysqli_fetch_assoc($query)) {
        $student_arr[] = $row;
    }

    echo json_encode($student_arr);
    exit;
}

if (isset($_POST['save_row'])) {
    $faculty_id = $_POST['faculty_id'];
    $student_id = $_POST['student_id'];

    $time_of_advisement = $_POST['time_of_advisement'];

    $id = $_POST['id'];

    if(empty($id)) {
        //    add to users
        $sql = "INSERT INTO advisor (`faculty_id`, `student_id`, `time_of_advisement`) VALUES ('$faculty_id', '$student_id', '$time_of_advisement')";

        if ($conn->query($sql)) {
            $ret['success'] = true;
        } else {
            $ret['message'] = "Error in student";
        }
    } else {
        // update
        $id = $_POST['id'];

        $sql = "UPDATE advisor SET faculty_id = '$faculty_id', student_id = '$student_id', time_of_advisement = '$time_of_advisement'";
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

    $sqlStudentMajors = "SELECT *";
    $sqlStudentMajors .= " FROM advisor";
    $sqlStudentMajors .= " WHERE id = '$edit_id' LIMIT 1";

    $query = mysqli_query($conn, $sqlStudentMajors);
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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">


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
            <div class="col-lg-5">
                <h3>Student - Majors</h3>
            </div>
            <div class="col-lg-3">
                <div class="form-group px-1">
                    Student: &nbsp;
                    <select class="student-selectpicker" id="student_id" data-live-search="true">
                    </select>
                </div>
            </div>

            <div class="col-lg-4 text-right" style="display: flex; justify-content: flex-end">
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
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Major ID</th>
                <th>Major Name</th>
                <th>Department ID</th>
                <th>Department Name</th>
                <th>Type</th>
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

                        <div class="col-sm-6 py-1">
                            <label class="col-form-label" for="faculty_id">Faculty</label>
                            <select class="faculty-selectpicker" id="faculty_id" data-live-search="true">
                            </select>
                        </div>
                        <div class="col-sm-6 py-1">
                            <label class="col-form-label" for="student_id">Student</label>
                            <select id="student_id" class="student-selectpicker" name="student_id"  data-live-search="true">
                            </select>
                        </div>
                        <div class="col-sm-6 py-1 form-group">
                            <label class="col-form-label" for="name">Time Of Advisement</label>
                            <input type="text" class="form-control" id="time_of_advisement" name="time_of_advisement" required/>
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
<div class="modal fade" id="delete-prereq-modal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h3 style="color: red;" class="text-center">Do you want to delete prereq info?</h3>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-success" onclick="onDelete()">Yes</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

<script src="../plugins/js/toastr.js"></script>
<script src="../plugins/js/nav.js"></script>
<script src="../js/administrator/student-majors.js"></script>

</body>
</html>
