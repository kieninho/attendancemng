@extends('layouts.app')

@section('header')
@include('elements.header')
@endsection

@section('content')
<div class="container-fluid row">

    <div class="col-md-2">
        <div class="list-group scrollbar overflow-auto my-2" style="max-height: 400px;">
            <a href="#" class="list-group-item list-group-item-action">Quản lý sinh viên trong lớp</a>
            @foreach($classes as $classItem)
            <a href="{{route('studentInClass',['classId'=>$classItem->id])}}" class="list-group-item list-group-item-warning list-group-item-action">{{$classItem->name}}</a>
            @endforeach
        </div>
    </div>

    <div class="col-md-10">
        <div class="top-box d-flex justify-content-between my-1" style="width:100%;">
            <h5>Danh sách sinh viên lớp: {{$class->name}}</h5>
            <div class="search-box" style="width:300px; height:30px">
                <form class="d-flex" action="{{route('studentInClass',['classId'=>$class->id])}}" method="get">
                    <input class="form-control me-2" type="text" name="keyword" placeholder="Tìm kiếm" aria-label="Search" value="{{$keyword}}">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </form>
            </div>
            <div class="button-box">
                <button type="button" id="delete-mul" class="btn btn-primary" disabled>Xóa nhiều</button>
                <button type="button" id="export" class="btn btn-primary ms-2"><a class="text-light" href="{{route('export.studentInClass',['classId'=>$class->id])}}">Xuất Excel</a></button>
                <button type="button" id="add-std-btn" class="btn btn-primary"><a class="text-light" href="{{route('add.studentsinclass',['id'=>$class->id])}}">Thêm SV</a></button>
            </div>
        </div>
        <table class="table table-hover table-striped mb-1">
            <thead>
                <tr>
                    <th scope="col">Stt</th>
                    <th scope="col">Mã SV</th>
                    <th scope="col">Tên</th>
                    <th scope="col">Email</th>
                    <th scope="col">Vào lớp</th>
                    <th scope="col" class="text-center">Chuyên cần</th>
                    <th scope="col"></th>
                    <th scope="col"><input class="form-check-input" type="checkbox" onclick="selectAll()" id="select-all"></th>
                </tr>
            </thead>

            <tbody>

                @foreach($students as $student)
                <tr>
                    <th scope="row" class="table-Info">{{ $loop->iteration }}</th>
                    <td class="table-Info">{{$student->code}}</td>
                    <td class="table-Info">{{$student->name}}</td>
                    <td class="table-Info">{{$student->email}}</td>
                    <td class="table-Info">{{$student->getJoinDate($class->id)}}</td>
                    <td class="table-Info text-center">{{$student->classAttendRate($class->id)}}%</td>
                    <td class="table-Info">
                        <a class="link-danger" href="{{route('delete.studentInClass',['classId'=>$class->id,'studentId'=>$student->id])}}">Xóa</a>
                        <span class="divider"></span>
                        <a class="link-primary" href="">Chi tiết</a>
                    </td>
                    <td class="table-Info"><input class="form-check-input" name="item_ids[]" type="checkbox" onclick="setCheckedSelectAll()" id="flexCheckChecked"></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{$students->links()}}
    </div>

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

<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cập nhật sinh viên lớp {{$class->name}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addForm" action="{{route('update.studentInClass',['classId'=>$class->id])}}" method="POST">
                @csrf
                <div class="modal-body">

                    <div class="student-container scrollbar" style="max-height:400px; overflow: auto;">
                        @foreach($allStudent as $item)
                        <div class="form-check">
                            <input class="form-check-input add-student-class" name="student_ids[]" type="checkbox" value="{{$item->id}}" id="student-{{$item->id}}">
                            <label class="form-check-label" for="student-{{$item->id}}">
                                {{$item->code." - ".$item->name}}
                            </label>
                        </div>
                        @endforeach
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
        $('#update-btn').click(function() {
            var students = <?= json_encode($students); ?>;
            for (var i = 0; i < students.length; i++) {
                idBox = "#student-" + students[i].id;
                $(idBox).prop("checked", true);
            }
        });

            $('input[name="item_ids[]"]').add($('#select-all')).on('change', function() {

                if ($('input[name="item_ids[]"]:checked').length > 0) {

                    $('#delete-mul').prop('disabled', false);
                } else {
                    $('#delete-mul').prop('disabled', true);
                }
            });


    });

    // getdata from server
    $('#updateModal').on('hidden.bs.modal', function() {
        $('#addForm')[0].reset();
    });
</script>
@endsection

@section('footer')
@include('elements.footer')
@endsection