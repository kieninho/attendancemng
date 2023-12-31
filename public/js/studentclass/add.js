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
        $('input[name="item_ids[]"]').add($('#select-all')).on('change', function() {

            if ($('input[name="item_ids[]"]:checked').length > 0) {

                $('#add-std-btn').prop('disabled', false);
            } else {
                $('#add-std-btn').prop('disabled', true);
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