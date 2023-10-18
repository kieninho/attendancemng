@extends('layouts.app')

@section('header')
@include('elements.header')
@endsection


@section('content')

<div class="container-fluid">
    <div class="top-box d-flex justify-content-between my-1" style="width:100%;">
        <h5>Quản lý giáo viên</h5>
        <div class="search-box" style="width:300px; height:30px">
            <form class="d-flex" action="{{route('teacher')}}" method="get">
                <input class="form-control me-2" type="text" name="keyword" placeholder="Tìm kiếm giáo viên" aria-label="Search" value="{{$keyword}}">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
            </form>
        </div>
        <form action="{{route('delete.teachers')}}" method="post">
            @csrf
            <div class="button-box">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Thêm GV</button>
                <button type="submit" class="btn btn-primary">Xóa GV đã chọn</button>
            </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-striped mb-1">
            <thead>
                <tr>
                    <th scope="col">Stt</th>
                    <th scope="col">Tên</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Ngày sinh</th>
                    <th scope="col" class="text-center">Số lớp dạy</th>
                    <th scope="col"></th>
                    <th scope="col"><input class="form-check-input" type="checkbox" onclick="selectAll()" id="select-all"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($teachers as $teacher)
                <tr>
                    <th scope="row" class="table-Info">{{ $loop->iteration }}</th>
                    <td class="table-Info">{{$teacher->name}}</td>
                    <td class="table-Info">{{$teacher->email}}</td>
                    <td class="table-Info">{{$teacher->phone}}</td>
                    <td class="table-Info">{{$teacher->birthday}}</td>
                    <td class="table-Info text-center">{{$teacher->countClasses()}}</td>
                    <td class="table-Info">
                        <span class="edit-button text-success cursor-pointer" data-bs-toggle="modal" data-id="{{$teacher->id}}" data-bs-target="#editModal">Sửa</span>
                        <span class="divider"></span>
                        <a class="link-danger" href="{{route('delete.teacher',['id'=>$teacher->id])}}">Xóa</a>
                        <span class="divider"></span>
                        <a class="link-primary" href="">Chi tiết</a>
                    </td>
                    <td class="table-Info"><input class="form-check-input" name="item_ids[]" type="checkbox" onclick="setCheckedSelectAll()" id="flexCheckChecked"></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </form>
        {{$teachers->links()}}

    </div>

    <div id="error-box" class="position-fixed bottom-0 end-0 p-3 fade" role="alert" style="z-index: 9999;">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="ps-1">
                @foreach ($errors->all() as $error)
                <li style="list-style-type:none;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm Giáo Viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('store.teacher')}}" method="POST">
                @csrf
                <div class="modal-body">

                    <div class="mb-1">
                        <label for="recipient-name" class="col-form-label">Tên giáo viên:</label>
                        <input type="text" name="name" class="form-control" id="add-teacher-name">
                    </div>

                    <div class="mb-1">
                        <label for="add-teacher-email" class="col-form-label">Email:</label>
                        <input type="email" class="form-control" name="email" id="add-teacher-email">
                    </div>

                    <div class="mb-1">
                        <label for="add-phone" class="col-form-label">Điện Thoại:</label>
                        <input type="tel" class="form-control" name="phone" id="add-phone" style="width: 200px;">
                    </div>

                    <div class="mb-1">
                        <label for="datetimepicker1" class="datetimepicker col-form-label">Ngày sinh:</label>
                        <input type="text" class="form-control" name="birthday" id="datetimepicker1" style="width: 150px;">
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

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sửa Thông Tin Giáo Viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('update.teacher')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="teacherId" name="teacherId">

                    <div class="mb-1">
                        <label for="edit-teacher-name" class="col-form-label">Tên giáo viên:</label>
                        <input type="text" name="name" class="form-control" id="edit-teacher-name">
                    </div>


                    <div class="mb-1">
                        <label for="edit-teacher-email" class="col-form-label">Email:</label>
                        <input type="email" class="form-control" name="email" id="edit-teacher-email" disabled>
                    </div>

                    <div class="mb-1">
                        <label for="edit-teacher-phone" class="col-form-label">Điện Thoại:</label>
                        <input type="tel" class="form-control" name="phone" id="edit-teacher-phone" style="width: 200px;">
                    </div>

                    <div class="mb-1">
                        <label for="datetimepicker2" class="datetimepicker col-form-label">Ngày sinh:</label>
                        <input type="text" class="form-control" name="birthday" id="datetimepicker2" style="width: 150px;">
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

<script>
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

</script>
@endsection

@section('footer')
@include('elements.footer')
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endsection