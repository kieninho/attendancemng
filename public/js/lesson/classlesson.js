
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

    function getTimeFromString(dateTimeString){
        const dateTime = new Date(dateTimeString)

        return dateTime.toTimeString().slice(0, 5);
    }

    function getDateFromString(dateTimeString){
        const dateTime = new Date(dateTimeString);
        const year = dateTime.getFullYear();
        const month = ("0" + (dateTime.getMonth() + 1)).slice(-2);
        const day = ("0" + dateTime.getDate()).slice(-2);
    
        const dateString = `${year}-${month}-${day}`;
        return dateString;
    }
