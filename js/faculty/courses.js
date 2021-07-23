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
    onLoadData(true);
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

function onLoadData(bInit = false) {
    let request = {};
    request.search_text = $('#search-text').val();

    if (bInit == true) {
        pagination.currentNumber = oldPagination.currentNumber;
        pagination.pageSize = oldPagination.pageSize;
        pagination.totalCount = oldPagination.totalCount;
    }
    request.start_number = pagination.currentNumber * pagination.pageSize;
    request.page_size = pagination.pageSize;

    request.course_id = $('#course_id').val();
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

function onSelectPicker(event) {
    event.preventDefault();

    let value = event.target.value;
    $('.course-selectpicker').selectpicker('val', value);

    onLoadData();
}


function onLoadInitSelecter() {
    let request = {};
    request.get_init_arr = true;

    $.ajax({
        method: "POST",
        url: window.location.href,
        data: request,
        dataType: 'json',
        success: function (res) {
            if (res != undefined) {
                let course_arr = res.course_arr;

                let courseHtml = '<select id="course_id" name="course_id" class="course-selectpicker" onchange="onSelectPicker(event)" data-live-search="true" required><option value="0">All</option>';
                course_arr.forEach(item => {
                    courseHtml += "<option value='" + item.id + "'>" + item.course_name + "</option>"
                });

                courseHtml += "</select>";

                let faculty_arr = res.faculty_arr;

                let facultyHtml = '<select id="faculty_id" name="faculty_id" class="faculty-selectpicker" data-live-search="true">';
                faculty_arr.forEach(item => {
                    facultyHtml += "<option value='" + item.id + "'>" + item.faculty_name + "</option>"
                });

                facultyHtml += "</select>";

                let period_arr = res.period_arr;

                let periodHtml = '<select id="period_id" name="period_id" class="faculty-selectpicker" data-live-search="true">';
                period_arr.forEach(item => {
                    periodHtml += "<option value='" + item.id + "'>" + item.time + "</option>"
                });

                periodHtml += "</select>";

                $('.bootstrap-select.course-').replaceWith(courseHtml);
                $('.bootstrap-select.faculty-').replaceWith(facultyHtml);
                $('.bootstrap-select.period-').replaceWith(periodHtml);


                $('.course-selectpicker').selectpicker();
                $('.faculty-selectpicker').selectpicker();
                $('.period-selectpicker').selectpicker();

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

function onAddNew() {

    $('#id').val(0);
    $('#room_num').val("");
    $('#building_name').val("");
    $('#available_seat').val("");
    $('#sem_term_year').val("");
    $('#section').val("");

    $('#add-modal-title').html('Add Row');
    $('#add-modal').modal('show');
}

function onEditRow(id) {
    let request = {};
    request.edit_id = id;
    request.get_row = true;

    $.ajax({
        method: "POST",
        url: window.location.href,
        data: request,
        dataType: 'json',
        success: function (res) {
            if (res != undefined) {
                let section_info = res.section;
                let time_slot_days = res.time_slot_days;

                let keys = Object.keys(section_info);

                for (let i = 0; i < keys.length; i++) {
                    $('#add-modal-title').html('Update Row');
                    $('#' + keys[i]).val(section_info[keys[i]] == null ? "" : section_info[keys[i]]);
                }

                time_slot_days.forEach(item => {
                    $('#' + item).prop('checked', true);
                });
                $('.course-selectpicker').selectpicker('val', section_info.course_id);
                $('#faculty_id').selectpicker('val', section_info.faculty_id);
                $('#period_id').selectpicker('val', section_info.period_id);

                $('#term').selectpicker('val', section_info.term);
                $('#year').selectpicker('val', section_info.year);

                $('#add-modal').modal('show');
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

function onSave(event) {
    event.preventDefault();

    if ($('#course_id').val() == 0) {
        alert('Please select course!');
        return;
    }

    $('#btn-save').addClass('disabled');
    $('#btn-cancel').addClass('disabled');

    let formData = $('#form-add').serializeArray();

    let request = {};
    request.week_days = [];
    formData.forEach(item => {
        if (item.name == 'week_day[]') {
            request.week_days.push(item.value);
        } else {
            request[item.name] = item.value;
        }
    });

    request.save_row = true;

    $.ajax({
        method: "POST",
        url: window.location.href,
        data: request,
        dataType: 'json',
        success: function (res) {
            if (res !== undefined && res.success == true) {
                toastr.success("Saved Successfully!");
                $('#add-modal').modal('hide');
                onLoadData();
            } else {
                toastr.error(res.message);
            }
        },
        complete: function () {
            $('#btn-save').removeClass('disabled');
            $('#btn-cancel').removeClass('disabled');
        }
    });

}

function onDeleteRow(id) {
    delete_id = id;
    $('#delete-modal').modal('show');
}

function onDelete() {
    let request = {};
    request.delete_id = delete_id;

    request.delete_row = true;

    $.ajax({
        method: "POST",
        url: window.location.href,
        data: request,
        dataType: 'json',
        success: function (res) {
            if (res !== undefined && res.success == true) {

                toastr.success("Deleted Successfully!");
                $('#delete-modal').modal('hide');
                onLoadData();
            } else {
                toastr.error("There are some issues!");
            }
        },
        complete: function () {
        }
    });

}

function onChangePageNumber(event) {
    let selectedNumber = parseInt($('#' + event.target.id).val());
    onSelectPagination(selectedNumber);

    onLoadData();
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
    $('.faculty-selectpicker').selectpicker();
    $('.period-selectpicker').selectpicker();
    $('.year-selectpicker').selectpicker('val', '2021');
    $('.term-selectpicker').selectpicker();

    onLoadInitSelecter();

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

        onLoadData();
    })
});
