@extends('layouts.app')

@section('header')
@include('elements.header')
@endsection


@section('content')
<div class="container-fluid">
    <div class="top-box d-flex justify-content-between my-1" style="width:100%;">
        <h5>Quản lý sinh viên</h5>
        <div class="search-box" style="width:300px; height:30px">
            <form class="d-flex" action="{{route('student')}}" method="get">
                <input class="form-control me-2" type="text" name="keyword" placeholder="Tìm kiếm sinh viên" aria-label="Search" value="{{$keyword}}">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
            </form>
        </div>
        <div class="button-box">
            <button type="button" class="btn btn-primary" id="delete-mul" disabled>Xóa nhiều</button>
            <button type="button" id="export" class="btn btn-primary ms-2"><a class="text-light" href="{{route('export.student')}}">Xuất Excel</a></button>
            <button type="button" class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#addModal">Thêm SV</button>
        </div>
    </div>
    <div class="table-responsive">

        <table class="table table-hover table-striped mb-1">
            <thead>
                <tr>
                    <th scope="col" class="text-center">Stt</th>
                    <th scope="col" class="text-center">Mã SV</th>
                    <th scope="col" class="text-center">Tên</th>
                    <th scope="col" class="text-center">Email</th>
                    <th scope="col" class="text-center">Ngày sinh</th>
                    <th scope="col" class="text-center">Chuyên cần</th>
                    <th scope="col"></th>
                    <th scope="col"><input class="form-check-input" type="checkbox" onclick="selectAll()" id="select-all"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr>
                    <th scope="row" class="table-Info">{{ $loop->iteration }}</th>
                    <td class="table-Info text-center">{{$student->code}}</td>
                    <td class="table-Info">{{$student->name}}</td>
                    <td class="table-Info">{{$student->email}}</td>
                    <td class="table-Info text-center">{{$student->birthday}}</td>
                    <td class="table-Info text-center">{{round(($student->lessons->count())/($student->countLessonInClass())*100)}}%{{$student->countLessonInClass()}}</td>
                    <td class="table-Info">
                        <span class="edit-button text-success cursor-pointer" data-bs-toggle="modal" data-id="{{$student->id}}" data-bs-target="#editModal">Sửa</span>
                        <span class="divider"></span>
                        <a class="link-danger" href="{{route('delete.student',['id'=>$student->id])}}">Xóa</a>
                        <span class="divider"></span>
                        <a class="link-primary" href="{{route('detail.student',['id'=>$student->id])}}">Chi tiết</a>
                    </td>
                    <td class="table-Info"><input class="form-check-input" name="item_ids[]" type="checkbox" onclick="setCheckedSelectAll()" id="flexCheckChecked"></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{$students->links()}}
    </div>
    <div id="error-box" class="position-fixed bottom-0 end-0 p-3 fade" role="alert" style="z-index: 9999;">
        @if ($errors->any())
        <div class="alert alert-danger px-2 py-1">
            <ul class="ps-1">
                @foreach ($errors->all() as $error)
                <li style="list-style-type:none;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</div>




<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm Sinh Viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('store.student')}}" method="POST">
                @csrf
                <div class="modal-body">

                    <div class="mb-1">
                        <label for="recipient-name" class="col-form-label">Tên sinh viên:</label>
                        <input type="text" name="name" class="form-control" id="add-student-name">
                    </div>
                    <div class="mb-1">
                        <label for="add-student-email" class="col-form-label">Email:</label>
                        <input type="email" class="form-control" name="email" id="add-student-email">
                    </div>

                    <div class="mb-1">
                        <label for="add-birthday" class="col-form-label">Ngày sinh:</label>
                        <input type="date" class="form-control" name="birthday" id="add-birthday" style="width: 150px;">
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

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sửa Thông Tin Sinh Viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('update.student')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="studentId" name="studentId">

                    <div class="mb-1">
                        <label for="edit-student-code" class="col-form-label">Mã SV:</label>
                        <input type="text" name="code" class="form-control" id="edit-student-code" style="width: 150px;" disabled>
                    </div>

                    <div class="mb-1">
                        <label for="edit-student-name" class="col-form-label">Tên sinh viên:</label>
                        <input type="text" name="name" class="form-control" id="edit-student-name">
                    </div>
                    <div class="mb-1">
                        <label for="edit-student-email" class="col-form-label">Email:</label>
                        <input type="email" class="form-control" name="email" id="edit-student-email">
                    </div>

                    <div class="mb-1">
                        <label for="edit-birthday" class="col-form-label">Ngày sinh:</label>
                        <input type="date" class="form-control" name="birthday" id="edit-birthday" style="width: 150px;">
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
<script src="{{asset('js/student/index.js')}}"></script>

<script>
    $(document).ready(function() {
        $('.edit-button').click(function() {
            var studentId = $(this).data('id'); // Lấy giá trị ID từ thuộc tính data-id của nút được click
            $('#studentId').val(studentId); // Gán giá trị ID vào hidden input

            $.ajax({
                url: '{{ route("get.student") }}/' + studentId,
                type: 'get',
                success: function(response) {
                    $('#edit-student-code').val(response.code);
                    $('#edit-student-name').val(response.name);
                    $('#edit-student-email').val(response.email);

                    let parts = response.birthday.split("/");
                    let formattedDate = `${parts[2]}-${parts[1].padStart(2, '0')}-${parts[0].padStart(2, '0')}`;
                    $('#edit-birthday').val(formattedDate);
                }
            });
        });

        $('input[name="item_ids[]"]').add($('#select-all')).on('change', function() {

            if ($('input[name="item_ids[]"]:checked').length > 0) {

                $('#delete-mul').prop('disabled', false);
            } else {
                $('#delete-mul').prop('disabled', true);
            }
        });
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