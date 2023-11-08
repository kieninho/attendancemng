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
            var teacherId = $(this).data('id'); // Lấy giá trị ID từ thuộc tính data-id của nút được click
            $('#teacherId').val(teacherId); // Gán giá trị ID vào hidden input
            route = $('#get-teacher').data('route');

            $.ajax({
                url: route + '/' + teacherId,
                type: 'get',
                success: function(response) {
                    $('#edit-teacher-name').val(response.name);
                    $('#edit-teacher-email').val(response.email);
                    $('#edit-teacher-phone').val(response.phone);

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
                    minlength: 3,
                    maxlength: 150
                },
                email: {
                    required: true,
                    email: true,
                    maxlength: 150,
                },
            },
            messages: {
                name: {
                    required: "Tên không được bỏ trống",
                    minlength: "Tên phải dài hơn 3 ký tự",
                    maxlength: "Tên quá dài"
                },
                email: {
                    required: "Email không được bỏ trống",
                    email: "Email không hợp lệ",
                    maxlength: "Email quá dài",
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
                    maxlength: 150
                },
            },
            messages: {
                name: {
                    required: "Tên không được bỏ trống",
                    minlength: "Tên phải dài hơn 3 ký tự",
                    maxlength: "Tên quá dài"
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