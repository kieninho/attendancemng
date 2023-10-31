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


