var errorAlert = document.getElementById('error-box');

// Thêm lớp 'show' để hiển thị div
errorAlert.classList.add('show');

// Tự động mờ và biến mất sau 3 giây
setTimeout(function () {
    errorAlert.classList.remove('show');
}, 3000);


// getdata from server
$(document).ready(function () {

    $('.form-check-input').click(function () {
        let studentId = $(this).data('id'); // Lấy giá trị ID từ thuộc tính data-id của nút được click
        let isChecked = $(this).is(':checked');
        let lessonId = $('#lesson-id').data('id');

        let value = 1;
        if (isChecked) {
            value = $(this).val();
        }


        $('.check-attend-' + studentId).not(this).prop('checked', false);
        route = $('#attend-lesson').data('route');

        $.ajax({
            url: route + '/' + lessonId + "/" + studentId + "/" + value,
            type: 'get',
            success: function (response) {
                console.log(route + '/' + lessonId + "/" + studentId + "/" + value);
                // AJAX request đã được gửi thành công
                $('.error-message').text("Cập nhật thành công");
            },
        });

    });


    // Nếu class kết thúc thì không cho điểm danh các tiết học
    let classActive = $('#class-active').data('active');
    if (!classActive) {
        $('td input[type="checkbox"]').prop('disabled', true);
    }


});
