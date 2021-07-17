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

    $sqlSection = "SELECT sc.*, c.course_name, concat(u.first_name, ' ', u.last_name) as faculty_name, concat(p.start_time, ' ~ ', P.end_time) as period_time";
    $sqlSection .= " FROM `section` as sc";
    $sqlSection .= " INNER JOIN course as c ON c.id = sc.course_id";
    $sqlSection .= " INNER JOIN faculty as f ON f.id = sc.faculty_id";
    $sqlSection .= " INNER JOIN users as u ON u.id = f.user_id";
    $sqlSection .= " INNER JOIN period as p ON p.id = sc.period_id";

    $sqlSection .= " WHERE sc.id > 0";

    $course_id = $_POST['course_id'];

    if (!empty($course_id)) {
        $sqlSection .= " AND (sc.course_id = '$course_id')";
    }

    if(!empty($search_text)) {
        $sqlSection .= " AND (sc.id LIKE '%$search_text%' OR sc.course_id LIKE '%$search_text%'";
        $sqlSection .= " OR sc.faculty_id LIKE '%$search_text%' OR sc.room_num LIKE '%$search_text%'";
        $sqlSection .= " OR sc.building_name LIKE '%$search_text%' OR sc.available_seat LIKE '%$search_text%'";
        $sqlSection .= " OR sc.year LIKE '%$search_text%' OR sc.section LIKE '%$search_text%'";
        $sqlSection .= " OR sc.term LIKE '%$search_text%'";
        $sqlSection .= " OR p.start_time LIKE '%$search_text%' OR p.end_time LIKE '%$search_text%')";
    }

    $totalQuery = mysqli_query($conn, $sqlSection);
    $total_count = mysqli_num_rows($totalQuery);

    $sqlSection .= " LIMIT $page_size OFFSET $start_number";

    $query = mysqli_query($conn, $sqlSection);

    $count = 0;
    $resultHtml = "";
    while($row = mysqli_fetch_assoc($query)){

        $section_id = $row['id'];

//        get days info
        $sqlDays = "SELECT * FROM time_slot_day WHERE section_id = '$section_id'";

        $queryDays = mysqli_query($conn, $sqlDays);

        $time_slot_days = [];
        while ($rowDays = mysqli_fetch_assoc($queryDays)) {
            $time_slot_days[] = $rowDays['week_day'];
        }

        $str_time_slot_days = "";
        if (!empty($time_slot_days)) {
            $str_time_slot_days = implode(", ", $time_slot_days);
        }

//        var_dump($row["prereq_course_name"]);
        $resultHtml .= '<tr id="row_' . $row["id"] . '">';
        $resultHtml .= '<td>'.$row["id"].'</td>';
        $resultHtml .= '<td>'. $row["course_id"].'</td>';
        $resultHtml .= '<td>'. $row["course_name"].'</td>';
        $resultHtml .= '<td>'.$row["faculty_id"].'</td>';
        $resultHtml .= '<td>'.$row["faculty_name"].'</td>';
        $resultHtml .= '<td>'.$row["room_num"].'</td>';
        $resultHtml .= '<td>'.$row["building_name"].'</td>';
        $resultHtml .= '<td>'.$row["available_seat"].'</td>';
        $resultHtml .= '<td>' . $str_time_slot_days . '</td>';
        $resultHtml .= '<td>'.$row['term'] . ' ' . $row['year'] .'</td>';
        $resultHtml .= '<td>'.$row["section"].'</td>';

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
        $sql  = "DELETE s.*, tsd.* FROM section as s";
        $sql .= " LEFT JOIN time_slot_day as tsd ON tsd.section_id = s.id";
        $sql .= " WHERE s.id = '".$delete_id."'";

        if ($conn->query($sql)) {
            $ret['success'] = true;
        }
    }

    echo json_encode($ret);
    exit;
}

if (isset($_POST['get_init_arr'])) {

    // get course arr

    $sql = "SELECT id, course_name FROM course";

    $query = mysqli_query($conn, $sql);
    $course_arr = [];

    while ($row = mysqli_fetch_assoc($query)) {
        $course_arr[] = $row;
    }

    // get period arry
    $sql = "SELECT id, concat(start_time, ' ~ ', end_time) as time FROM period";
    $query = mysqli_query($conn, $sql);
    $period_arr = [];

    while ($row = mysqli_fetch_assoc($query)) {
        $period_arr[] = $row;
    }

    // get faculty arr
    $sql = "SELECT f.id, concat(u.first_name, ' ', u.last_name) as faculty_name";
    $sql .= " FROM faculty as f";
    $sql .= " INNER JOIN users as u ON u.id = f.user_id";
    $query = mysqli_query($conn, $sql);
    $faculty_arr = [];

    while ($row = mysqli_fetch_assoc($query)) {
        $faculty_arr[] = $row;
    }

    $ret = [
            'course_arr' => $course_arr,
        'period_arr' => $period_arr,
        'faculty_arr' => $faculty_arr
    ];
    echo json_encode($ret);
    exit;
}

if (isset($_POST['get_faculty_arr'])) {
//    get prereq course

    $sql = "SELECT id, course_name FROM course";

    $query = mysqli_query($conn, $sql);
    $course_arr = [];

    while ($row = mysqli_fetch_assoc($query)) {
        $course_arr[] = $row;
    }

    echo json_encode($course_arr);
    exit;
}

if (isset($_POST['delete_prereq'])) {
    $delete_id = $_POST['delete_id'];

    $ret = [
        'success' => false
    ];

    if (!empty($delete_id)) {
        $sql  = "UPDATE course SET prereq_course_id = 0 WHERE id = '".$delete_id."'";
        if ($conn->query($sql)) {
            $ret['success'] = true;
        }
    }

    echo json_encode($ret);
    exit;
}

if (isset($_POST['save_row'])) {
    $course_id = $_POST['course_id'];
    $faculty_id = $_POST['faculty_id'];

    $room_num = $_POST['room_num'];
    $building_name = $_POST['building_name'];
    $available_seat = $_POST['available_seat'];
    $year = $_POST['year'];
    $term = $_POST['term'];
    $section = $_POST['section'];
    $period_id = $_POST['period_id'];

    $week_days = $_POST['week_days'];

    $id = $_POST['id'];

    $ret = [
            'success' => false,
            'message' => '',
    ];


    if(empty($id)) {

        // insert

        //    add to users
        $sql = "INSERT INTO section (`course_id`, `faculty_id`, `room_num`, `building_name`, `available_seat`, `year`, `term`, `section`, `period_id`)";
        $sql .= " VALUES ('$course_id', '$faculty_id', '$room_num', '$building_name', '$available_seat', '$year', '$term', '$section', '$period_id')";

        if ($conn->query($sql)) {

            $section_id = $conn->insert_id;
            $ret['success'] = true;
            // insert into
            foreach ($week_days as $week_day) {
                $sql = "INSERT INTO time_slot_day (`section_id`, `week_day`) VALUES ('$section_id', '$week_day')";
                if (!$conn->query($sql)) {
                    $ret['success'] = false;
                    break;
                }
            }

        } else {
            $ret['message'] = "Error in Adding";
        }
    } else {
        // update
//        $id = $_POST['id'];
//
//        $sql = "UPDATE course SET prereq_course_id = '$prereq_course_id', department_id = '$department_id', course_name = '$course_name', course_credits = '$course_credits'";
//        $sql .= " WHERE id = $id";
//
//        if ($conn->query($sql)) {
//            $ret['success'] = true;
//        } else {
//            $ret['message'] = "Error in student";
//        }
    }

    echo json_encode($ret);
    exit;
}

if (isset($_POST['get_row'])) {
    $edit_id = $_POST['edit_id'];

    $sqlSection = "SELECT c.*, pc.course_name as prereq_course_name, d.name as department_name";
    $sqlSection .= " FROM `course` as c LEFT JOIN course as pc ON pc.id = c.prereq_course_id";
    $sqlSection .= " LEFT JOIN department as d ON d.id = c.department_id";
    $sqlSection .= " WHERE c.id = '$edit_id' LIMIT 1";

    $query = mysqli_query($conn, $sqlSection);
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
                <h3>Sections</h3>
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

                <button type="button" class="btn btn-sm btn-success" onclick="onAddNew()">
                    <i class="fa fa-plus"></i> &nbsp;Add New
                </button>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Course ID</th>
                <th>Course Name</th>
                <th>Faculty ID</th>
                <th>Faculty Name</th>
                <th>Room Num</th>
                <th>Building Name</th>
                <th>Available Seat</th>
                <th>Sem Term Year</th>
                <th>Time Slot Day</th>
                <th>Section</th>

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
                            <label class="col-form-label" for="prereq_course_id">Prereq Course</label>
                            <select class="course-selectpicker" id="course_id" data-live-search="true">
                            </select>

                        </div>
                        <div class="col-sm-6 py-1">
                            <label class="col-form-label" for="department_id">Faculty</label>
                            <select class="faculty-selectpicker" name="faculty_id" id="faculty_id" data-live-search="true">
                            </select>
                        </div>
                        <div class="col-sm-6 py-1 form-group">
                            <label class="col-form-label" for="room_num">Room Number</label>
                            <input type="text" class="form-control" id="room_num" name="room_num" required/>
                        </div>
                        <div class="col-sm-6 py-1 form-group">
                            <label class="col-form-label" for="building_name">Building Name</label>
                            <input type="text" class="form-control" id="building_name" name="building_name" required/>
                        </div>
                        <div class="col-sm-6 py-1 form-group">
                            <label class="col-form-label" for="available_seat">Available Seat</label>
                            <input type="text" class="form-control" id="available_seat" name="available_seat" required/>
                        </div>
                        <div class="col-sm-6 py-1 form-group">
                            <label class="col-form-label" for="available_seat">Section</label>
                            <input type="text" class="form-control" id="section" name="section" required/>
                        </div>
                        <div class="col-sm-6 py-1 form-group">
                            <label class="col-form-label" for="available_seat">Term</label>
                            <select class="term-selectpicker" name="term" id="term" data-live-search="true">
                                <option value="Spring">Spring</option>
                                <option value="Fall">Fall</option>
                            </select>
                        </div>
                        <div class="col-sm-6 py-1 form-group">
                            <label class="col-form-label" for="available_seat">Year</label>
                            <select class="year-selectpicker" name="year" id="year" data-live-search="true">
                                <option value="2020">2020</option>
                                <option value="2021">2021</option>
                                <option value="2022">2022</option>
                                <option value="2023">2023</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                            </select>
                        </div>
                        <div class="col-sm-6 py-1 form-group">
                            <div class="row" style="width: 100%">
                                <div class="col-sm-6">
                                    <label class="col-form-label" for="available_seat">Select Days: </label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="checkbox">
                                        <label><input type="checkbox" name="week_day[]" value="Monday">&nbsp;Monday</label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" name="week_day[]" value="Tuesday">&nbsp;Tuesday</label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" name="week_day[]" value="Wednesday">&nbsp;Wednesday</label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" name="week_day[]" value="Thursday">&nbsp;Thursday</label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" name="week_day[]" value="Friday">&nbsp;Friday</label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" name="week_day[]" value="Saturday">&nbsp;Saturday</label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" name="week_day[]" value="Sunday">&nbsp;Sunday</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 py-1">
                            <label class="col-form-label" for="period_id">Period</label>
                            <select class="period-selectpicker" name="period_id" id="period_id"  data-live-search="true">
                            </select>
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
<script src="../js/administrator/sections.js"></script>


</body>
</html>
