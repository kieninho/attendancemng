
var errorAlert = document.getElementById('error-box');

// Thêm lớp 'show' để hiển thị div
errorAlert.classList.add('show');

// Tự động mờ và biến mất sau 3 giây
setTimeout(function () {
    errorAlert.classList.remove('show');
}, 3000);


function selectAll() {
    var checkboxes = document.getElementsByName("item_ids[]");
    var selectAllCheckbox = document.getElementById("select-all");

    for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = selectAllCheckbox.checked;
    }
}

function setCheckedSelectAll() {
    var checkboxes = document.getElementsByName("item_ids[]");
    var selectAllCheckbox = document.getElementById("select-all");

    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked == false) {
            selectAllCheckbox.checked = false;
            return;
        }
        selectAllCheckbox.checked = true;
    }
}

function getTimeFromString(dateTimeString) {
    const dateTime = new Date(dateTimeString)

    return dateTime.toTimeString().slice(0, 5);
}

function getDateFromString(dateTimeString) {
    const dateTime = new Date(dateTimeString);
    const year = dateTime.getFullYear();
    const month = ("0" + (dateTime.getMonth() + 1)).slice(-2);
    const day = ("0" + dateTime.getDate()).slice(-2);

    const dateString = `${year}-${month}-${day}`;
    return dateString;
}

$(document).ready(function () {
    $('.edit-button').click(function () {
        let lessonId = $(this).data('id'); // Lấy giá trị ID từ thuộc tính data-id của nút được click
        $('#lessonId').val(lessonId); // Gán giá trị ID vào hidden input

        route = $('#get-lesson').data('route');

        $.ajax({
            url: route + '/' + lessonId,
            type: 'get',
            success: function (response) {
                $('#edit-lesson-name').val(response.name);
                $('#edit-lesson-description').val(response.description);

                $('#edit-start-time').val(getTimeFromString(response.start_at));
                $('#edit-end-time').val(getTimeFromString(response.end_at));
                $('#edit-date').val(getDateFromString(response.start_at));
            }
        });

        route2 = $('#get-teacher-lesson').data('route');
        // teacher lesson
        $.ajax({
            url: route2 + '/' + lessonId,
            type: 'get',
            dataType: 'json',
            success: function (response) {
                $.each(response, function (index, item) {
                    // Duyệt qua từng bản ghi
                    idBox = "#ck-teacher-" + item.teacher_id;
                    $(idBox).prop("checked", true);
                });
            }
        });

    });

    $('input[name="item_ids[]"]').add($('#select-all')).on('change', function () {

        if ($('input[name="item_ids[]"]:checked').length > 0) {

            $('#delete-mul').prop('disabled', false);
        } else {
            $('#delete-mul').prop('disabled', true);
        }
    });

    // Đoạn này xử lý dùng shift để chọn nhiều checkbox
    let lastChecked = null;

    $('table').on('click', 'input[type="checkbox"]', function (e) {
        if (lastChecked && e.shiftKey) {
            let checkboxes = $('input[type="checkbox"]');
            let start = checkboxes.index(lastChecked);
            let end = checkboxes.index(this);
            checkboxes.slice(Math.min(start, end), Math.max(start, end) + 1).prop('checked', true);
        }

        lastChecked = this;
    });

    $('#addForm').validate({
        rules: {
            name: {
                required: true,
            },
            start: {
                required: true,
            },
            end: {
                required: true,
            },
            date: {
                required: true,
            },
        },
        messages: {
            name: {
                required: "Tên không được bỏ trống",
            },
            start: {
                required: "Thời gian bắt đầu không được bỏ trống",
            },
            end: {
                required: "Thời gian kết thúc không được bỏ trống",
            },
            date: {
                required: "Ngày học không được bỏ trống",
            },
        },
        submitHandler: function (form) {
            // Nếu form hợp lệ, gửi form tới controller
            form.submit();
        }
    });

    $('#editForm').validate({
        rules: {
            name: {
                required: true,
            },
            start: {
                required: true,
            },
            end: {
                required: true,
            },
            date: {
                required: true,
            },
        },
        messages: {
            name: {
                required: "Tên không được bỏ trống",
            },
            start: {
                required: "Thời gian bắt đầu không được bỏ trống",
            },
            end: {
                required: "Thời gian kết thúc không được bỏ trống",
            },
            date: {
                required: "Ngày học không được bỏ trống",
            },
        },
        submitHandler: function (form) {
            // Nếu form hợp lệ, gửi form tới controller
            form.submit();
        }
    });

});

$('#addModal').on('hidden.bs.modal', function () {
    $('#addForm')[0].reset();
});

$('#editModal').on('hidden.bs.modal', function () {
    $('#editForm')[0].reset();
});


function showAlert(event) {
    event.preventDefault();
    alert("Buổi học chưa bắt đầu!");

    return false;
}