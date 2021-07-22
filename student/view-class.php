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

    $sqlSection = "SELECT sc.id, sc.room_num, sc.faculty_id, sc.building_name, concat(sc.term, ' ', sc.year) as semester, sc.section, c.course_name, concat(u.first_name, ' ', u.last_name) as faculty_name, concat(p.start_time, ' ~ ', p.end_time) as period_time";
    $sqlSection .= " FROM `enrollment` as e";
    $sqlSection .= " LEFT JOIN student as s ON s.id = e.student_id";
    $sqlSection .= " LEFT JOIN section as sc ON sc.id = e.section_id";
    $sqlSection .= " LEFT JOIN course as c ON c.id = sc.course_id";
    $sqlSection .= " LEFT JOIN period as p ON p.id = sc.period_id";
    $sqlSection .= " LEFT JOIN faculty as f ON f.id = sc.faculty_id";
    $sqlSection .= " LEFT JOIN users as u ON u.id = f.user_id";

    $sqlSection .= " WHERE s.user_id = '$user_id'";
    $sqlSection .= " AND e.date_enrolled LIKE '%2021%'";

    $course_id = $_POST['course_id'];

    if (!empty($course_id)) {
        $sqlSection .= " AND (sc.course_id = '$course_id')";
    }

    if(!empty($search_text)) {
        $sqlSection .= " AND (sc.id LIKE '%$search_text%' OR c.course_name LIKE '%$search_text%'";
        $sqlSection .= " OR u.first_name LIKE '%$search_text%' OR u.last_name LIKE '%$search_text%'";
        $sqlSection .= " OR sc.room_num LIKE '%$search_text%' OR sc.building_name LIKE '%$search_text%'";
        $sqlSection .= " OR p.start_time LIKE '%$search_text%' OR p.end_time LIKE '%$search_text%'";
        $sqlSection .= " OR sc.term LIKE '%$search_text%' OR sc.year LIKE '%$search_text%'";
        $sqlSection .= " OR sc.section LIKE '%$search_text%' OR sc.faculty_id LIKE '%$search_text%')";
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
        $resultHtml .= '<td>'. $row["course_name"].'</td>';
        $resultHtml .= '<td>'.$row["faculty_name"].'</td>';
        $resultHtml .= '<td>'.$row["room_num"].'</td>';
        $resultHtml .= '<td>'.$row["building_name"].'</td>';
        $resultHtml .= '<td>'.$row['period_time'] .'</td>';
        $resultHtml .= '<td>' . $str_time_slot_days . '</td>';
        $resultHtml .= '<td>' . $row['semester'] . '</td>';
        $resultHtml .= '<td>' . $row['section'] . '</td>';
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

    // get course arr

    $sql = "SELECT c.id, c.course_name";
    $sql .= " FROM enrollment as e";
    $sql .= " LEFT JOIN section as sc ON sc.id = e.section_id";
    $sql .= " LEFT JOIN course as c ON c.id = e.section_id";
    $sql .= " LEFT JOIN student as s ON s.id = e.student_id";
    $sql .= " WHERE s.user_id = '$user_id' AND e.date_enrolled LIKE '%2021%'";

    $query = mysqli_query($conn, $sql);
    $course_arr = [];

    while ($row = mysqli_fetch_assoc($query)) {
        $course_arr[] = $row;
    }

    echo json_encode($course_arr);
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

    // check
    $sql = "SELECT * FROM section WHERE course_id = '$course_id' AND faculty_id = '$faculty_id' AND term = '$term' AND year = '$year'";
    $sql .= " AND period_id = '$period_id' AND section = '$section'";

    if (!empty($id)) {
        $sql .= " AND id != '$id'";
    }

    $query = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query) > 0) {

        $bExit = false;
        while ($row = mysqli_fetch_assoc($query)) {
            $test_id = $row['id'];

            $sql = "SELECT * FROM time_slot_day WHERE section_id = '$test_id'";

            $test_query = mysqli_query($conn, $sql);

            while ($test_row = mysqli_fetch_assoc($test_query)) {
                if (in_array($test_row['week_day'], $week_days)) {
                    $bExit = true;
                    break;
                }
            }

            if ($bExit == true) {
                break;
            }
        }

        if ($bExit == true) {
            $ret['success'] = false;
            $ret['message'] = "Current data already exist! Please select another!";

            echo json_encode($ret);
            exit;
        }
    }


    if(empty($id)) {

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
        $sql = "UPDATE section SET course_id = '$course_id', faculty_id = '$faculty_id', room_num = '$room_num', building_name = '$building_name',";
        $sql .= " available_seat = '$available_seat', year = '$year', term = '$term', section = '$section', period_id = '$period_id'";
        $sql .= " WHERE id = $id";

        if ($conn->query($sql)) {

            $sql = "DELETE FROM time_slot_day WHERE section_id = '$id'";
            $conn->query($sql);

            foreach ($week_days as $week_day) {
                $sql = "INSERT INTO time_slot_day (`section_id`, `week_day`) VALUES ('$id', '$week_day')";
                if (!$conn->query($sql)) {
                    $ret['success'] = false;
                    break;
                }
            }

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

    $sqlSection = "SELECT *";
    $sqlSection .= " FROM `section`";
    $sqlSection .= " WHERE id = '$edit_id' LIMIT 1";

    $query = mysqli_query($conn, $sqlSection);
    $section = mysqli_fetch_assoc($query);

//    get slot days
    $sql = "SELECT * FROM time_slot_day WHERE section_id = '$edit_id'";
    $query = mysqli_query($conn, $sql);

    $time_slot_days = [];
    while ($row = mysqli_fetch_assoc($query)) {
        $time_slot_days[] = $row['week_day'];
    }

    $ret = [
            'section' => $section,
        'time_slot_days' => $time_slot_days
    ];

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
                <h3>View Schedule</h3>
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
                <th>Course Name</th>
                <th>Faculty Name</th>
                <th>Room Number</th>
                <th>Building Name</th>
                <th>Period Time</th>
                <th>Slot Days</th>
                <th>Semester</th>
                <th>Section</th>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

<script src="../plugins/js/toastr.js"></script>
<script src="../plugins/js/nav.js"></script>
<script src="../js/student/view-class.js"></script>


</body>
</html>
