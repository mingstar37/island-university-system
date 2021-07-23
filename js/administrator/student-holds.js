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

    request.student_id = $('#student_id').val();
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
    $('.student-selectpicker').selectpicker('val', value);

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
                let student_arr = res;

                let studentHtml = '<select id="student_id" name="student_id" class="student-selectpicker" onchange="onSelectPicker(event)" data-live-search="true"><option value="0">All</option>';
                student_arr.forEach(item => {
                    studentHtml += "<option value='" + item.id + "'>" + item.first_name + " " + item.last_name + "</option>"
                });

                studentHtml += "</select>";

                $('.bootstrap-select.student-').replaceWith(studentHtml);

                $('.student-selectpicker').selectpicker();

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
    $('#hold_date').val("");
    $('#hold_type').val("");

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

                let student_id  = res.student_id ;
                $('.student-selectpicker').selectpicker('val', student_id );

                // let student_id = res.student_id;
                // onLoadStudentSelectPicker(student_id, student_id);

                let keys = Object.keys(res);

                for (let i = 0; i < keys.length; i++) {
                    $('#add-modal-title').html('Update Row');
                    $('#' + keys[i]).val(res[keys[i]] == null ? "" : res[keys[i]]);
                }

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

    if ($('#student_id').val() == 0) {
        alert("Select Student!");
        return;
    }

    $('#btn-save').addClass('disabled');
    $('#btn-cancel').addClass('disabled');

    let formData = $('#form-add').serializeArray();

    let request = {};
    formData.forEach(item => {
        request[item.name] = item.value;
    });
    request.student_id = $('#student_id').val();
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
    $('.student-selectpicker').selectpicker();

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

        onLoadData();
    })
});
