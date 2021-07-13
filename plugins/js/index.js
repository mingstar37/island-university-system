var totalLimitCount = 3;
var gLimitTime = 31; // seconds

var interval = null;

var bEnabled = true;

function sendEmail(email, confirmType, bReset = true) {
    var data = {
        email: email,
        type: confirmType,
        confirmEmail: true
    };

    $('#btn_confirm_email').addClass('disabled');
    $.ajax({
        method: "POST",
        url: "./index.php",
        data: data,
        success: function (res) {
            if (res !== undefined) {
                let result = JSON.parse(res);

                if (result.success === true) {
                    toastr.success("Sent email successfully!");

                    if (bReset === true) {
                        $('.carousel').carousel(1);
                    }
                }
                else {
                    toastr.error(result.error);
                }
            }
        },
        complete: function () {
            $('#btn_confirm_email').removeClass('disabled');
        }
    });
}

function onSendConfirmEmail(e) {
    e.preventDefault();

    let confirmEmail = $('#confirm_email').val();
    let confirmType = $('#confirm_type').val();
    sendEmail(confirmEmail, confirmType);

    return false;
}

function onResendEmail() {
    let confirmEmail = $('#confirm_email').val();
    let confirmType = $('#confirm_type').val();
    sendEmail(confirmEmail, confirmType, false);
}

function onSendConfirmNumber(e) {
    e.preventDefault();

    let confirmNumber = $('#confirm_number').val();
    let email = $('#confirm_email').val();
    let type = $('#confirm_type').val();
    var data = {
        number: confirmNumber,
        email: email,
        type: type,
        confirmNumber: true
    };

    $('#btn_confirm_number').addClass('disabled');
    $.ajax({
        method: "POST",
        url: "./index.php",
        data: data,
        success: function (res) {
            if (res !== undefined) {
                let result = JSON.parse(res);

                if (result.success == true) {
                    $('.carousel').carousel(2);
                } else {
                    toastr.error(result.error);
                }
            }
        },
        complete: function () {
            $('#btn_confirm_number').removeClass('disabled');
        }
    });

    return false;
}

function onSendNewPassword(e) {
    e.preventDefault();

    let new_password = $('#new_password').val();
    let new_confirm_password = $('#new_confirm_password').val();

    if (new_confirm_password !== new_password) {
        toastr.warning('Please confirm new password');
        $('#new_confirm_password').val('');
        return false;
    }

    let number = $('#confirm_number').val();
    let email = $('#confirm_email').val();
    let type = $('#confirm_type').val();

    var data = {
        newPassword: new_password,
        number : number,
        email: email,
        type: type,
        confirmPassword: true
    };

    $('#btn_confirm_password').addClass('disabled');
    $.ajax({
        method: "POST",
        url: "./index.php",
        data: data,
        success: function (res) {
            if (res !== undefined) {
                let result = JSON.parse(res);

                if (result.success == true) {
                    toastr.success("Changed the password successfully!");
                    $('#resetPasswordModal').modal('hide');
                    bEnabled = true;
                    totalLimitCount = 3;
                } else {
                    toastr.error(result.error);
                }
            }
        },
        complete: function () {
            $('#btn_confirm_number').removeClass('disabled');
        }
    });


    return false;
}

function onRemoveErrors() {
    $('#error-message').html('');
    $('#limit-time').html('');
}
function showBlockTime(realLimit = 0) {

    if (realLimit !== 0) {
        $('.limit-time').val(`Please try again after ${realLimit}s.`);
        $('.input').addClass('disable-login');
        bEnabled = false;
        interval = setInterval(function () {
            realLimit--;
            if (realLimit < 0) {
                $('#limit-time').html('');
                $('.input').removeClass('disable-login');
                bEnabled = true;
                clearInterval(interval);
            } else {
                $('#limit-time').html(`Please try again after <span style="color:red">${realLimit}</span> seconds.`);
            }
        }, 1000);
    } else {
        let existLimitTime  = window.localStorage.getItem('limitTime');

        if (existLimitTime !== undefined && existLimitTime !== null) {
            let limitTime = existLimitTime - getTimeStamp(0);

            if (limitTime <= 0) {
                window.localStorage.removeItem('limitTime');
            } else {
                let realLimit = Math.floor(limitTime / 1000);

                $('.limit-time').val(`Please try again after ${realLimit}s.`);
                $('.input').addClass('disable-login');
                bEnabled = false;
                interval = setInterval(function () {
                    realLimit--;
                    if (realLimit < 0) {
                        $('#limit-time').html('');
                        $('.input').removeClass('disable-login');
                        bEnabled = true;
                        clearInterval(interval);
                    } else {
                        $('#limit-time').html(`Please try again after <span style="color:red">${realLimit}</span> seconds.`);
                    }
                }, 1000);
            }
        }
    }
}

function getTimeStamp(plusSeconds) {
    let curTimestamp = Date.now();

    if (plusSeconds > 0) {
        return curTimestamp + 1000 * plusSeconds;
    }

    return curTimestamp;
}

function onLogin(e) {
    e.preventDefault();

    if (bEnabled == false) {
        return;
    }

    let user_email = $('#user_email').val();
    let user_password = $('#user_password').val();
    let user_type = $('#user_type').val();

    var data = {
        user_email,
        user_password,
        user_type,
        login: true
    };

    $.ajax({
        method: "POST",
        url: "./index.php",
        data: data,
        success: function (res) {
            if (res !== undefined) {
                let result = JSON.parse(res);

                if (result.success === true) {
                    window.location.href = "./" + result.type.toLowerCase() + "/index.php";
                }
                else {
                    totalLimitCount--;
                    $('#error-message').html(result.error);
                    if (totalLimitCount < 1) {
                        $('#error-message').html('');
                        window.localStorage.setItem('limitTime', getTimeStamp(gLimitTime));
                        showBlockTime(gLimitTime);
                        totalLimitCount = 3;
                    }
                }
            }
        },
        complete: function () {

        }
    });

    return false;
}

$(document).ready(function () {
    showBlockTime();

    $('#resetPassword').click(function () {
        $('.carousel').carousel(0);
        $('#resetPasswordModal').modal({backdrop: 'static', keyboard: false, show: true});
        // stop carousel auto play
    });
});