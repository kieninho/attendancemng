var errorAlert = document.getElementById('error-box');

    // Thêm lớp 'show' để hiển thị div
    errorAlert.classList.add('show');

    // Tự động mờ và biến mất sau 3 giây
    setTimeout(function() {
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

    $(document).ready(function() {
        $('.edit-button').click(function() {
            var studentId = $(this).data('id'); // Lấy giá trị ID từ thuộc tính data-id của nút được click
            $('#studentId').val(studentId); // Gán giá trị ID vào hidden input

            route = $('#get-student').data('route');

            $.ajax({
                url: route + '/' + studentId,
                type: 'get',
                success: function(response) {
                    $('#edit-student-code').val(response.code);
                    $('#edit-student-name').val(response.name);
                    $('#edit-student-email').val(response.email);

                    let parts = response.birthday.split("/");
                    let formattedDate = `${parts[2]}-${parts[1].padStart(2, '0')}-${parts[0].padStart(2, '0')}`;
                    $('#edit-birthday').val(formattedDate);
                }
            });
        });

        $('input[name="item_ids[]"]').add($('#select-all')).on('change', function() {

            if ($('input[name="item_ids[]"]:checked').length > 0) {

                $('#delete-mul').prop('disabled', false);
            } else {
                $('#delete-mul').prop('disabled', true);
            }
        });

        $('#addForm').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 3,
                    maxlength: 100
                },
                email: {
                    required: true,
                    email: true,
                    maxlength: 150
                },
            },
            messages: {
                name: {
                    required: "Tên lớp không được bỏ trống",
                    minlength: "Tên lớp phải nhiều hơn 2 ký tự"
                },
                email: {
                    required: "Email không được bỏ trống",
                    email: "Email không hợp lệ",
                    maxlength: "Độ dài vượt quá 150 ký tự"
                },
            },
            submitHandler: function(form) {
                // Nếu form hợp lệ, gửi form tới controller
                form.submit();
            }
        });

        $('#editForm').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 3,
                    maxlength: 100
                },
                email: {
                    required: true,
                    email: true,
                    maxlength: 150
                },
            },
            messages: {
                name: {
                    required: "Tên lớp không được bỏ trống",
                    minlength: "Tên lớp phải nhiều hơn 2 ký tự"
                },
                email: {
                    required: "Email không được bỏ trống",
                    email: "Email không hợp lệ",
                    maxlength: "Độ dài vượt quá 150 ký tự"
                },
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