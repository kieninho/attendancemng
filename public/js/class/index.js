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

$(document).ready(function () {
    $('.edit-button').click(function () {
        var classId = $(this).data('id'); // Lấy giá trị ID từ thuộc tính data-id của nút được click
        $('#classId').val(classId); // Gán giá trị ID vào hidden input
        console.log(classId);

        $.ajax({
            url: '{{ route("get.class") }}/' + classId,
            type: 'get',
            success: function (response) {
                $('#edit-class-name').val(response.name);
                $('#edit-class-description').val(response.description);
            }
        });
    });
});

$('#addModal').on('hidden.bs.modal', function () {
    $('#addForm')[0].reset();
});

$('#editModal').on('hidden.bs.modal', function () {
    $('#editForm')[0].reset();
});