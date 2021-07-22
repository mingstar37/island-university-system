<?php
session_start();
if (empty($_SESSION['user_id']) || empty($_SESSION['user_email']) || empty($_SESSION['type'])) {
    header('location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

include '../connection.php';

if (isset($_POST['load_data'])) {
    $search_text = $_POST['search_text'];
    $start_number = $_POST['start_number'];
    $page_size = $_POST['page_size'];

    $sqlStudent = "SELECT s.id, s.course_id, s.room_num, s.building_name, concat(s.term, ' ', s.year) as semester, s.section, c.course_name";
    $sqlStudent .= " FROM `course` as c";
    $sqlStudent .= " LEFT JOIN `section` as s ON s.course_id = c.id";
    $sqlStudent .= " LEFT JOIN faculty as f ON f.id = s.faculty_id";
    $sqlStudent .= " WHERE f.user_id = '$user_id'";

    $course_id = $_POST['course_id'];
    if (!empty($course_id)) {
        $sqlStudent .= " AND c.id = '$course_id'";
    }

    if(!empty($search_text)) {
        $sqlStudent .= " AND (s.id LIKE '%$search_text%' OR s.course_id LIKE '%$search_text%'";
        $sqlStudent .= " OR c.course_name LIKE '%$search_text%' OR s.term LIKE '%$search_text%'";
        $sqlStudent .= " OR s.year LIKE '%$search_text%' OR s.section LIKE '%$search_text%'";
        $sqlStudent .= " OR s.room_num LIKE '%$search_text%' OR s.building_name LIKE '%$search_text%')";
    }

    $totalQuery = mysqli_query($conn, $sqlStudent);
    $total_count = mysqli_num_rows($totalQuery);

    $sqlStudent .= " LIMIT $page_size OFFSET $start_number";

    $query = mysqli_query($conn, $sqlStudent);

    $count = 0;
    $resultHtml = "";
    while($row = mysqli_fetch_assoc($query)){

        $resultHtml .= '<tr id="row_' . $row["id"] . '">';
        $resultHtml .= '<td>'. $row["id"] . '</td>';
        $resultHtml .= '<td>'. $row["course_id"] . '</td>';
        $resultHtml .= '<td>'. $row["course_name"] . '</td>';
        $resultHtml .= '<td>'. $row["room_num"].'</td>';
        $resultHtml .= '<td>'. $row['building_name'] . '</td>';
        $resultHtml .= '<td>'. $row['semester'] . '</td>';
        $resultHtml .= '<td>'. $row["section"].'</td>';
        $resultHtml .= '<td><button type="button" class="btn btn-sm btn-success" onclick="onShowDetail(' . $row["id"] . ', \'' . $row['course_name'] . '\')" title="View Roster Detail Info"><i class="fa fa-info-circle"></i></td>';
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

if (isset($_POST['get_init_arr'])) {

//    get prereq course

    $sql = "SELECT c.id, c.course_name";
    $sql .= " FROM course as c LEFT JOIN section as s ON s.course_id = c.id";
    $sql .= " LEFT JOIN faculty as f ON f.id = s.faculty_id";
    $sql .= " WHERE f.user_id = '$user_id'";
    $sql .= " GROUP BY c.id";

    $query = mysqli_query($conn, $sql);

    $student_arr = [];

    while ($row = mysqli_fetch_assoc($query)) {
        $student_arr[] = $row;
    }

    echo json_encode($student_arr);
    exit;
}

if (isset($_POST['get_detail_info'])) {
    $section_id = $_POST['section_id'];

    $ret = [];

//    get student holds
    $sqlAttentance = "SELECT a.*, concat(u.first_name, ' ', u.last_name) as student_name";
    $sqlAttentance .= " FROM attendance as a";
    $sqlAttentance .= " LEFT JOIN student as s ON s.id = a.student_id";
    $sqlAttentance .= " LEFT JOIN users as u ON u.id = s.user_id";

    $sqlAttentance .= " WHERE a.section_id = '$section_id'";
    $sqlAttentance .= " ORDER BY a.date_attended DESC";

    $query = mysqli_query($conn, $sqlAttentance);

    $html = "";
    $count = 0;
    while ($row = mysqli_fetch_assoc($query)) {
        $html .= '<tr>';
        $html .= '<td>'.$row["id"].'</td>';
        $html .= '<td>'. $row["student_id"].'</td>';
        $html .= '<td>'. $row["student_name"].'</td>';
        $html .= '<td>'. $row["date_attended"].'</td>';
        $html .= '<td>'. $row["present"].'</td>';
        $html .= '</tr>';

        $count ++;
    }

    if ($count < 1) {
        $html .= '<tr>
                            <td colspan="5">There is no data</td></tr>';
    }

    $ret['attendanceHtml'] = $html;

    echo json_encode($ret);
    exit;

//
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
    include "./sidebar.php";
    ?>
    <div class="main-page">
        <div class="row table-toolbar">
            <div class="col-lg-5">
                <h3>Rosters</h3>
            </div>
            <div class="col-lg-3">
                <div class="form-group px-1">
                    Course: &nbsp;
                    <select class="course-selectpicker" id="course_id" data-live-search="true">
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
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Course ID</th>
                <th>Course Name</th>
                <th>Room Number</th>
                <th>Building</th>
                <th>Semester</th>
                <th>Section</th>
                <th width="100px">Action</th>
            </tr>
            </thead>
            <tbody id="table-body">

            </tbody>
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
<div class="modal fade" id="detail-modal" tabindex="-1" role="dialog" aria-labelledby="addModal" aria-hidden="true" data-backdrop="false" style="background: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row py-1">
                    <h3 class="text-center " style="margin: auto; color: red" id="detail-modal-title"></h3>
                </div>

                <div class="row" style="padding: 20px;max-height: 400px; overflow: auto">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Date Attended</th>
                            <th>Present</th>
                        </tr>
                        </thead>
                        <tbody id="attendance-table-body">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="btn-cancel">Close</button>
            </div>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

<script src="../plugins/js/toastr.js"></script>
<script src="../plugins/js/nav.js"></script>
<script src="../js/faculty/rosters.js"></script>

</body>
</html>
