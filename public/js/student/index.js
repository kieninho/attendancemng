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
            console.log(studentId);

            $.ajax({
                url: '{{ route("get.student") }}/' + studentId,
                type: 'get',
                success: function(response) {
                    $('#edit-student-code').val(response.code);
                    $('#edit-student-name').val(response.name);
                    $('#edit-student-email').val(response.email);
                    $('#datetimepicker2').val(response.birthday);
                }
            });
        });
    });


    flatpickr("#datetimepicker1", {
        allowInput: true,
        enableTime: false,
        dateFormat: "d/m/Y",
    });

    flatpickr("#datetimepicker2", {
        allowInput: true,
        enableTime: false,
        dateFormat: "d/m/Y",
    });

    $('#addModal').on('hidden.bs.modal', function() {
        $('#addForm')[0].reset();
    });

    $('#editModal').on('hidden.bs.modal', function() {
        $('#editForm')[0].reset();
    });