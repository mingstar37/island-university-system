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

    $sqlStudentHolds = "SELECT dd.*";
    $sqlStudentHolds .= " FROM `due_dates` as dd";
    $sqlStudentHolds .= " WHERE dd.id > 0";

    $year = $_POST['year'];
    $term = $_POST['term'];

    if (!empty($year)) {
        $sqlStudentHolds .= " AND dd.year = '$year'";
    }

    if (!empty($term)) {
        $sqlStudentHolds .= " AND dd.term = '$term'";
    }

    if(!empty($search_text)) {
        $sqlStudentHolds .= " AND (dd.id LIKE '%$search_text%' OR dd.year LIKE '%$search_text%'";
        $sqlStudentHolds .= " OR dd.term LIKE '%$search_text%' OR dd.start_date LIKE '%$search_text%'";
        $sqlStudentHolds .= " OR dd.end_date LIKE '%$search_text%')";
    }

    $sqlStudentHolds .= " ORDER BY dd.year DESC, dd.term";

    $totalQuery = mysqli_query($conn, $sqlStudentHolds);
    $total_count = mysqli_num_rows($totalQuery);

    $sqlStudentHolds .= " LIMIT $page_size OFFSET $start_number";

    $query = mysqli_query($conn, $sqlStudentHolds);

    $count = 0;
    $resultHtml = "";
    while($row = mysqli_fetch_assoc($query)){

        $checked = $row['status'] ? 'checked' : '';
//        var_dump($row["prereq_course_name"]);
        $resultHtml .= '<tr id="row_' . $row["id"] . '">';
        $resultHtml .= '<td>'.$row["id"].'</td>';
        $resultHtml .= '<td>'. $row["term"].'</td>';
        $resultHtml .= '<td>'. $row['year'] . '</td>';
        $resultHtml .= '<td>'. $row['start_date'] . '</td>';
        $resultHtml .= '<td>'.$row["end_date"].'</td>';
        $resultHtml .= '<td><input type="checkbox" onchange="onUpdateStatus(event, ' . $row["id"] . ')" style="width: 28px; height: 28px;" class="form-control-md"' . $checked . ' value="' . $row["status"] . '"></td>';
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

if (isset($_POST['update_status'])) {
    $update_id = $_POST['update_id'];
    $status = $_POST['status'];

    $sql = "UPDATE due_dates SET `status` = '$status' WHERE id = '$update_id'";

    $ret = [
            'success' => false,
        'message' => ''
    ];

    if ($conn->query($sql)) {
        $ret['success'] = true;
    } else {
        $ret['success'] = false;
    }

    echo json_encode($ret);
    exit;
}

if (isset($_POST['delete_row'])) {
    $delete_id = $_POST['delete_id'];

    $ret = [
        'success' => false
    ];

    if (!empty($delete_id)) {
        $sql  = "DELETE FROM due_dates WHERE id = '".$delete_id."'";
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
    $year = $_POST['year'];
    $term = $_POST['term'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $id = $_POST['id'];

//    check
    $sql = "SELECT * FROM due_dates WHERE year = '$year' AND term = '$term'";

    if (!empty($id)) {
        $sql .= " AND id <> '$id'";
    }

    $ret = [
            'success' => false,
        'message' => ''
    ];

    $query = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query) > 0) {
        $ret['success'] = false;
        $ret['message'] = "Current data already exists!";
        echo json_encode($ret);
        exit;
    }

    if(empty($id)) {
        //    add to users
        $sql = "INSERT INTO due_dates (`year`, `term`, `start_date`, `end_date`) VALUES ('$year', '$term', '$start_date', '$end_date')";

        if ($conn->query($sql)) {
            $ret['success'] = true;
        } else {
            $ret['message'] = "Error in student";
        }
    } else {
        // update
        $id = $_POST['id'];

        $sql = "UPDATE due_dates SET year = '$year', term = '$term', start_date = '$start_date', end_date = '$end_date'";
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

    $sqlStudentHolds = "SELECT *";
    $sqlStudentHolds .= " FROM due_dates";
    $sqlStudentHolds .= " WHERE id = '$edit_id' LIMIT 1";

    $query = mysqli_query($conn, $sqlStudentHolds);
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
            <div class="col-lg-3">
                <h3>Due - Dates</h3>
            </div>
            <div class="col-lg-5">
                <div class="form-group px-1">
                    Semester: &nbsp;
                    <select class="term-selectpicker" id="term" name="year" onchange="onSelectTermPicker(event)" data-live-search="true">
                        <option value="0">All</option>
                        <option value="Spring">Spring</option>
                        <option value="Fall">Fall</option>
                    </select>
                    &nbsp;
                    Year: &nbsp;
                    <select class="year-selectpicker" id="year" name="year" data-live-search="true">
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
                <th>Semester</th>
                <th>Year</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
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
                            <label class="col-form-label" for="term">Semester:</label>
                            <select id="term" class="term-selectpicker" onchange="onSelectTermPicker(event)" name="term"  data-live-search="true">
                                <option value="0">All</option>
                                <option value="Spring">Spring</option>
                                <option value="Fall">Fall</option>
                            </select>
                        </div>
                        <div class="col-sm-6 py-1">
                            <label class="col-form-label" for="student_id">Year:</label>
                            <select id="year" class="year-selectpicker" name="year"  data-live-search="true">
                            </select>
                        </div>
                        <div class="col-sm-6 py-1 form-group">
                            <label class="col-form-label" for="start_date">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required/>
                        </div>
                        <div class="col-sm-6 py-1 form-group">
                            <label class="col-form-label" for="end_date">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required/>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

<script src="../plugins/js/toastr.js"></script>
<script src="../plugins/js/nav.js"></script>
<script src="../js/administrator/due-dates.js"></script>


</body>
</html>
