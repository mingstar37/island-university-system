let pagination = {
    totalCount: 0,
    currentNumber: 0,
    pageSize: 12
};

let oldPagination = {
    totalCount: 0,
    currentNumber: 0,
    pageSize: 12
};

let delete_id = 0;
let editId = 0;

function onSearchKeyup(event) {
    if (event.keyCode !== 13) {
        return;
    }

    onSearch();
}

function onSearch() {
    onSelectPagination(1);
    $('#page-select').val(1);
    onLoadData();
}

function onSetPageNumberSelect() {
    let totalPageCount = Math.ceil(pagination.totalCount / pagination.pageSize);

    let html = "";
    for (let i = 1; i <= totalPageCount; i++) {
        html += "<option value='" + i + "'>" + i + "</option>";
    }

    $('#page-select').html(html);

    $('#page-select').val(pagination.currentNumber + 1);
}


function onLoadStudentSelectPicker(student_id, default_val = 0) {

    let request = {};
    request.student_id = student_id;
    request.student_id = default_val;
    request.get_student_arr = true;

    $.ajax({
        method: "POST",
        url: window.location.href,
        data: request,
        dataType: 'json',
        success: function (res) {
            if (res != undefined) {
                let student_arr = res;

                let studentHtml = '<select class="student-selectpicker" name="student_id" id="student_id" data-live-search="true">';
                student_arr.forEach(item => {
                    studentHtml += "<option value='" + item.id + "'>" + item.student_name + "</option>"
                });

                studentHtml += "</select>";

                $('.bootstrap-select.student-').replaceWith(studentHtml);

                if (default_val != 0) {
                    $('#student_id').selectpicker('val', default_val);
                } else {
                    $('#student_id').selectpicker();
                }
            } else {
                toastr.error('Error');
            }
        },
        complete: function () {
            $('#btn-save').removeClass('disabled');
            $('#btn-cancel').removeClass('disabled');
        }
    });
}

function onLoadData(bInit = true) {
    let request = {};
    request.search_text = $('#search-text').val();

    if (bInit == true) {
        pagination.currentNumber = oldPagination.currentNumber;
        pagination.totalCount = oldPagination.totalCount;
        pagination.pageSize = oldPagination.pageSize;
    }

    request.start_number = pagination.currentNumber * pagination.pageSize;
    request.page_size = pagination.pageSize;

    request.course_id = $('.course-selectpicker').val();
    request.load_data = true;


    $.ajax({
        method: "POST",
        url: window.location.href,
        data: request,
        dataType: 'json',
        success: function (res) {
            if (res !== undefined) {
                pagination.totalCount = res.total_count;

                if (pagination.totalCount <= pagination.pageSize) {
                    $('#pagination-wrapper').hide();
                } else {
                    $('#pagination-wrapper').show();
                    onSelectPagination(pagination.currentNumber + 1);
                }
                onSetPageNumberSelect();
                $('#table-body').html(res.html);
            }
        },
        complete: function () {
        }
    });
}

function onShowDetail(student_id, student_name) {

    let request = {};
    request.student_id = student_id;
    request.get_detail_info = true;

    $.ajax({
        method: "POST",
        url: window.location.href,
        data: request,
        dataType: 'json',
        success: function (res) {
            $('#detail-modal-title').html(student_name + " Detail Info");

            $('#hold-table-body').html(res.holdHtml);
            $('#advisor-table-body').html(res.advisorHtml);
            $('#history-table-body').html(res.historyHtml);
            $('#enrollment-table-body').html(res.enrollmentHtml);

            $('#detail-modal').modal('show');
        },
        complete: function () {
        }
    });
}

function onSelectPicker(event) {
    event.preventDefault();

    let value = event.target.value;
    $('.student-selectpicker').selectpicker('val', value);

    // onLoadStudentSelectPicker(value);

    onLoadData();
}

function onLoadInitSelector() {
    let request = {};
    request.get_init_arr = true;

    $.ajax({
        method: "POST",
        url: window.location.href,
        data: request,
        dataType: 'json',
        success: function (res) {
            if (res != undefined) {
                let course_arr = res;

                let courseHtml = '<select id="course_id" class="course-selectpicker" onchange="onSelectPicker(event)" data-live-search="true"><option value="0">All</option>';
                course_arr.forEach(item => {
                    courseHtml += "<option value='" + item.id + "'>" + item.course_name + "</option>"
                });

                courseHtml += "</select>";

                $('.bootstrap-select.course-').replaceWith(courseHtml);

                $('.course-selectpicker').selectpicker();

                onLoadData();
            } else {
                toastr.error('Error');
            }
        },
        complete: function () {
            $('#btn-save').removeClass('disabled');
            $('#btn-cancel').removeClass('disabled');
        }
    });
}

function onChangePageNumber(event) {
    let selectedNumber = parseInt($('#' + event.target.id).val());
    onSelectPagination(selectedNumber);

    onLoadData(false);
}

function onSelectPagination(selectedNumber) {
    let nameArr = ["pagination-one", 'pagination-two', 'pagination-three'];

    let totalPageCount = Math.ceil(pagination.totalCount / pagination.pageSize);
    pagination.currentNumber = selectedNumber - 1;

    $('.page-item').removeClass('active');

    let curIndex = selectedNumber % 3;

    if (curIndex == 0) {
        curIndex = 3;
    }

    for (let i = selectedNumber - curIndex + 1; i <= selectedNumber - curIndex + 3; i++) {
        let tempIndex = i - (selectedNumber - curIndex + 1);
        $('#' + nameArr[tempIndex]).html(i);

        if (i <= totalPageCount) {
            $('#' + nameArr[tempIndex]).parent().show();
        } else {
            $('#' + nameArr[tempIndex]).parent().hide();
        }

        if (i === selectedNumber) {
            $('#' + nameArr[tempIndex]).parent().addClass('active');
        }
    }
}

$(document).ready(function () {
    $('.course-selectpicker').selectpicker();
    // $('#student_id').selectpicker();

    onLoadInitSelector();

    $('.page-item a').click(function (event) {
        let totalPageCount = Math.ceil(pagination.totalCount / pagination.pageSize);

        let id = event.target.id;
        let cur_num = pagination.currentNumber + 1;

        if (id === 'pagination-one' || id === 'pagination-two' || id === 'pagination-three'){
            $('.page-item').removeClass('active');
            $(event.target.parentElement).addClass('active');

            cur_num = parseInt(event.target.innerHTML);
        } else if (id === 'pagination-prev') {
            if (cur_num == 1) {
                return;
            }
            cur_num--;

        } else if (id === 'pagination-next') {
            if (cur_num == totalPageCount) {
                return;
            }
            cur_num++;
        }

        onSelectPagination(cur_num);
        $('#page-select').val(cur_num);

        onLoadData(false);
    })
});
