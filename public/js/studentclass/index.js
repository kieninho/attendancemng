var errorAlert = document.getElementById('error-box');

    // Thêm lớp 'show' để hiển thị div
    errorAlert.classList.add('show');

    // Tự động mờ và biến mất sau 3 giây
    setTimeout(function() {
        errorAlert.classList.remove('show');
    }, 3000);


    function selectAll() {
        let checkboxes = document.getElementsByName("item_ids[]");
        let selectAllCheckbox = document.getElementById("select-all");

        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = selectAllCheckbox.checked;
        }
    }

    function setCheckedSelectAll() {
        let checkboxes = document.getElementsByName("item_ids[]");
        let selectAllCheckbox = document.getElementById("select-all");


        for (var i = 0; i < checkboxes.length; i++) {

            if (checkboxes[i].checked == false) {
                selectAllCheckbox.checked = false;
                return;
            }
            selectAllCheckbox.checked = true;
        }
    }

    //
    $(document).ready(function() {

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
    });

    // getdata from server
    $('#updateModal').on('hidden.bs.modal', function() {
        $('#addForm')[0].reset();
    });