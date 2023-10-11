@extends('layouts.app')

@section('header')
@include('elements.header')
@endsection

@section('content')
<div class="container-fluid row">
    <div class="col-md-2">
        <div class="list-group">
            <a href="#" class="list-group-item list-group-item-action">Danh sách lớp</a>
            <a href="#" class="list-group-item list-group-item-warning list-group-item-action">Class 1</a>
            <a href="#" class="list-group-item list-group-item-warning list-group-item-action">Class 2</a>
            <a href="#" class="list-group-item list-group-item-warning list-group-item-action">Class 3</a>
        </div>
    </div>

    <div class="table-responsive col-md-10">
        <table class="table table-hover table-striped mb-1">
            <thead>
                <tr>
                    <th scope="col">Stt</th>
                    <th scope="col">Tên</th>
                    <th scope="col">Mô tả</th>
                    <th scope="col">Thời gian</th>
                    <th scope="col">GV</th>
                    <th scope="col">Sĩ số</th>
                    <th scope="col">Action</th>
                    <th scope="col"><input class="form-check-input" type="checkbox" onclick="selectAll()" id="select-all"></th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <th scope="row" class="table-Info">1</th>
                    <td class="table-Info">Lesson 1</td>
                    <td class="table-Info">Giới thiệu lung tung</td>
                    <td class="table-Info">17h40 - 21h10 10/4/1222</td>
                    <td class="table-Info">Nguyễn Văn Hoàng, Lê Minh Đức</td>
                    <td class="table-Info">18/23</td>
                    <td class="table-Info">
                        <span class="edit-button text-success cursor-pointer" data-bs-toggle="modal" data-id="1" data-bs-target="#editClassModal">Sửa</span>
                        <a class="link-danger" href="#">Xóa</a>
                        <a class="link-primary" href="">Chi tiết</a>
                    </td>
                    <td class="table-Info"><input class="form-check-input" name="item_ids[]" type="checkbox" onclick="setCheckedSelectAll()" id="flexCheckChecked"></td>
                </tr>
            </tbody>
        </table>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClassModal">Thêm buổi học</button>
    </div>

</div>


<div class="modal fade" id="addClassModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tạo lớp</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('store.class')}}" method="POST">
                @csrf
                <div class="modal-body">

                    <div class="mb-1">
                        <label for="recipient-name" class="col-form-label">Tên lớp:</label>
                        <input type="text" name="name" class="form-control" id="add-class-name">
                    </div>
                    <div class="mb-1">
                        <label for="description-class-name" class="col-form-label">Chi tiết:</label>
                        <textarea class="form-control" name="description" id="description-class-name"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editClassModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tạo lớp</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('update.class')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="classId" name="classId">
                    <div class="mb-1">
                        <label for="recipient-name" class="col-form-label">Tên lớp:</label>
                        <input type="text" name="name" class="form-control" id="edit-class-name">
                    </div>
                    <div class="mb-1">
                        <label for="description-class-name" name="name" class="col-form-label">Chi tiết:</label>
                        <textarea class="form-control" name="description" id="edit-class-description"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- <script>
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
            var classId = $(this).data('id'); // Lấy giá trị ID từ thuộc tính data-id của nút được click
            $('#classId').val(classId); // Gán giá trị ID vào hidden input
            console.log(classId);
            
            $.ajax({
                url: '{{ route("get.class") }}/' + classId,
                type: 'get',
                success: function(response) {
                    $('#edit-class-name').val(response.name);
                    $('#edit-class-description').val(response.description);
                }
            });
        });
    });
</script> -->
@endsection

@section('footer')
@include('elements.footer')
@endsection