
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

    function ToDatetime(strDatetime) {
        return new Date(strDatetime);
    }

    function ToTime(strDatetime) {
        var dateTime = new Date(strDatetime);
        var hours = dateTime.getHours();
        var minutes = dateTime.getMinutes();

        var time = hours + ":" + (minutes < 10 ? "0" + minutes : minutes);
        return time;
    }

    function ToDate(strDatetime) {
        var dateTime = new Date(strDatetime);
        var day = dateTime.getDate();
        var month = dateTime.getMonth() + 1;
        var year = dateTime.getFullYear();

        return day + "/" + month + "/" + year;
    }

    flatpickr("#add-date", {
        allowInput: true,
        enableTime: false,
        dateFormat: "d/m/Y",
    });

    flatpickr("#edit-date", {
        allowInput: true,
        enableTime: false,
        dateFormat: "d/m/Y",

    });

    flatpickr("#add-start-time", {
        allowInput: true,
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true
    });

    flatpickr("#add-end-time", {
        allowInput: true,
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true
    });

    flatpickr("#edit-start-time", {
        allowInput: true,
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true
    });

    flatpickr("#edit-end-time", {
        allowInput: true,
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true
    });


    // getdata from server
    $(document).ready(function() {
        $('.edit-button').click(function() {
            var lessonId = $(this).data('id'); // Lấy giá trị ID từ thuộc tính data-id của nút được click
            $('#lessonId').val(lessonId); // Gán giá trị ID vào hidden input
            $.ajax({
                url: '{{ route("get.lesson") }}/' + lessonId,
                type: 'get',
                success: function(response) {
                    $('#edit-lesson-name').val(response.name);
                    $('#edit-lesson-description').val(response.description);
                    $('#edit-date').val(ToDate(response.start_at));
                    $('#edit-start-time').val(ToTime(response.start_at));
                    $('#edit-end-time').val(ToTime(response.end_at));
                }
            });

            // teacher lesson
            $.ajax({
                url: '{{ route("get.teacherLesson") }}/' + lessonId,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    $.each(response, function(index, item) {
                        // Duyệt qua từng 
                        idBox = "#ck-teacher-" + item.teacher_id;
                        console.log(idBox);
                        $(idBox).prop("checked", true);

                        // ...
                    });
                }
            });

        });
    });

    $('#addModal').on('hidden.bs.modal', function() {
        $('#addForm')[0].reset();
    });

    $('#editModal').on('hidden.bs.modal', function() {
        $('#editForm')[0].reset();
    });