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
            console.log(teacherId);
            $.ajax({
                url: '{{ route("get.teacher") }}/' + teacherId,
                type: 'get',
                success: function(response) {
                    $('#edit-teacher-name').val(response.name);
                    $('#edit-teacher-email').val(response.email);
                    $('#edit-teacher-phone').val(response.phone);
                    $('#datetimepicker2').val(response.birthday);
                }
            });
        });
    });


    flatpickr("#datetimepicker1", {
        allowInput: true,
        enableTime: false,
        dateFormat: "d/m/Y",
        minDate: "01/01/1945",
        maxDate: "31/12/2022",
    });

    flatpickr("#datetimepicker2", {
        allowInput: true,
        enableTime: false,
        dateFormat: "d/m/Y",
        minDate: "01/01/1945",
        maxDate: "31/12/2022",

    });

    $('#addModal').on('hidden.bs.modal', function() {
        $('#addForm')[0].reset();
    });

    $('#editModal').on('hidden.bs.modal', function() {
        $('#editForm')[0].reset();
    });