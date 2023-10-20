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
        $.ajax({
            url: '{{ route("get.user") }}/' + userId,
            type: 'get',
            success: function(response) {
                $('#edit-user-name').val(response.name);
                $('#edit-user-email').val(response.email);
                $('#userId').val(response.id);
            }
        });
    });
});