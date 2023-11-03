// Hiện thị lỗi
var errorAlert = document.getElementById('error-box');

// Thêm lớp 'show' để hiển thị div
errorAlert.classList.add('show');

// Tự động mờ và biến mất sau 3 giây
setTimeout(function() {
    errorAlert.classList.remove('show');
}, 3000);

$(document).ready(function() {
    $('.edit-button').click(function() {
        var userId = $(this).data('id'); // Lấy giá trị ID từ thuộc tính data-id của nút được click
        $('#userId').val(userId); // Gán giá trị ID vào hidden input

        route = $('#get-user').data('route');
        $.ajax({
            url: route + '/' + userId,
            type: 'get',
            success: function(response) {
                $('#edit-user-name').val(response.name);
                $('#edit-user-email').val(response.email);
                $('#userId').val(response.id);
            }
        });
    });

    $('.reset-pass-button').click(function() {
        var userId = $(this).data('id'); // Lấy giá trị ID từ thuộc tính data-id của nút được click
        $('#userIdReset').val(userId); // Gán giá trị ID vào hidden input
        console.log(userId);
        route = $('#get-user').data('route');
        $.ajax({
            url: route + '/' + userId,
            type: 'get',
            success: function(response) {
                $('#email-text').text(response.email);
            }
        });
    });

    $('#editForm').validate({
        rules: {
            name: {
                required: true,
                minlength: 3,
                maxlength: 150
            },
        },
        messages: {
            name: {
                required: "Tên User không được bỏ trống",
                minlength: "Tên phải dài hơn 3 ký tự",
                maxlength: "Tên quá dài"
            },

        },
        submitHandler: function(form) {
            // Nếu form hợp lệ, gửi form tới controller
            form.submit();
        }
    });

    $('#addForm').validate({
        rules: {
            name: {
                required: true,
                minlength: 3,
                maxlength: 150
            },
            email: {
                required: true,
                email: true,
                maxlength: 150,
            },
            password: {
                required: true,
                minlength: 6
            },
            password2: {
                required: true,
                equalTo: "#password"
            }
        },
        messages: {
            name: {
                required: "Tên User không được bỏ trống",
                minlength: "Tên phải dài hơn 3 ký tự",
                maxlength: "Tên quá dài"
            },
            email: {
                required: "Email không được bỏ trống",
                email: "Email không hợp lệ",
                maxlength: "Email quá dài",
            },
            password: {
                required: "Mật khẩu không được bỏ trống",
                minlength: "Độ dài mật khẩu lớn hơn 6 ký tự"
            },
            password2: {
                required: "Nhập lại mật khẩu",
                equalTo: "Không trùng khớp với mật khẩu"
            }
        },
        submitHandler: function(form) {
            // Nếu form hợp lệ, gửi form tới controller
            form.submit();
        }
    });

    $('#resetPasswordForm').validate({
        rules: {
            newpass:{
                required: true,
                minlength: 6,
            },
            newpass2: {
                required: true,
                equalTo: "#new-pass"
            },
        },
        messages: {
            newpass: {
                required: "Nhập mật khẩu mới",
                minlength: "Độ dài mật khẩu lớn hơn 6 ký tự",
            },
            newpass2: {
                required: "Nhập lại mật khẩu",
                equalTo: "Không trùng khớp",
            }

        },
        submitHandler: function(form) {
            // Nếu form hợp lệ, gửi form tới controller
            form.submit();
        }
    });
});

$('#addModal').on('hidden.bs.modal', function() {
    $('#addForm')[0].reset();
});

$('#editModal').on('hidden.bs.modal', function() {
    $('#editForm')[0].reset();
});

$('#resetPasswordModal').on('hidden.bs.modal', function() {
    $('#resetPasswordForm')[0].reset();
});