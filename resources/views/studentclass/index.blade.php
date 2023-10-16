@extends('layouts.app')

@section('header')
@include('elements.header')
@endsection

@section('content')
<div class="container-fluid">

    <div class="col-md-10">
        <div class="top-box d-flex justify-content-between my-1" style="width:100%; height:24px">
            <h5>Danh sách buổi học lớp: {{$class->name}}</h5>
            <div class="search-box" style="width:300px; height:30px">
                <form class="d-flex" action="{{route('classLesson',['classId'=>$class->id])}}" method="get">
                    <input class="form-control me-2" type="text" name="keyword" placeholder="Tìm kiếm" aria-label="Search" value="{{$keyword}}">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </form>
            </div>
            <div class="button-box">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Thêm buổi học</button>
            </div>
        </div>
        <table class="table table-hover table-striped mb-1">
            <thead>
                <tr>
                    <th scope="col">Stt</th>
                    <th scope="col">Tên</th>
                    <th scope="col">Mô tả</th>
                    <th scope="col">Thời gian</th>
                    <th scope="col">GV</th>
                    <th scope="col">Sĩ số</th>
                    <th scope="col"></th>
                    <th scope="col"><input class="form-check-input" type="checkbox" onclick="selectAll()" id="select-all"></th>
                </tr>
            </thead>

            <tbody>

                @foreach($lessons as $lesson)
                <tr>
                    <th scope="row" class="table-Info">{{ $loop->iteration }}</th>
                    <td class="table-Info">{{$lesson->name}}</td>
                    <td class="table-Info">{{$lesson->description}}</td>
                    <td class="table-Info">{{$lesson->getStartAndEnd()}}</td>
                    <td class="table-Info">
                        @foreach($lesson->teachers as $teacher)
                        @if ($loop->last)
                        {{$teacher->name}}
                        @continue
                        @endif
                        {{$teacher->name.", "}}
                        @endforeach
                    </td>
                    <td class="table-Info">{{$lesson->students->count()??0}}/40</td>
                    <td class="table-Info">
                        <span class="edit-button text-success cursor-pointer" data-bs-toggle="modal" data-id="{{$lesson->id}}" data-bs-target="#editModal">Sửa</span>
                        <span class="divider"></span>
                        <a class="link-danger" href="{{route('delete.lesson',['id'=>$lesson->id])}}">Xóa</a>
                        <span class="divider"></span>
                        <a class="link-primary" href="">Chi tiết</a>
                    </td>
                    <td class="table-Info"><input class="form-check-input" name="item_ids[]" type="checkbox" onclick="setCheckedSelectAll()" id="flexCheckChecked"></td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

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

<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tạo Buổi Học</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addForm" action="{{route('store.lesson',['classId'=>$class->id])}}" method="POST">
                @csrf
                <div class="modal-body">

                    <div class="mb-1">
                        <label for="add-lesson-name" class="col-form-label">Tên:</label>
                        <input type="text" name="name" class="form-control" id="add-lesson-name">
                        <input type="hidden" name="class_id" value="{{$class->id}}">
                    </div>

                    <div class="mb-3">
                        <label for="add-lesson-description" class="col-form-label">Chi tiết:</label>
                        <textarea class="form-control" name="description" id="add-lesson-description"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="col-form-label">Thời gian:</label>
                        <input type="text" name="start" class="form-control" id="add-start-time" style="width:100px; display:inline;" placeholder="Bắt đầu">
                        <input type="text" name="end" class="form-control" id="add-end-time" style="width:100px; display:inline;" placeholder="Kết thúc">
                        <input type="text" name="date" class="form-control" id="add-date" style="width:150px; display:inline;" placeholder="Ngày">
                    </div>

                    <div class="mb-1">
                        <label class="col-form-label">Giáo viên:</label>
                    </div>
                    <div class="teachers-container scrollbar" style="max-height:100px; overflow: auto;">
                        @foreach($teachers as $teacher)
                        <div class="form-check">
                            <input class="form-check-input add-lesson-teacher" name="teacher_ids[]" type="checkbox" value="{{$teacher->id}}" id="{{$teacher->id}}">
                            <label class="form-check-label" for="{{$teacher->id}}">
                                {{$teacher->name}}
                            </label>
                        </div>
                        @endforeach
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
                <h5 class="modal-title">Tạo lớp</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" action="{{route('update.lesson')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-1">
                        <label for="edit-lesson-name" class="col-form-label">Tên:</label>
                        <input type="text" name="name" class="form-control" id="edit-lesson-name">
                        <input type="hidden" id="lessonId" name="lessonId" value="">
                    </div>

                    <div class="mb-3">
                        <label for="edit-lesson-description" class="col-form-label">Chi tiết:</label>
                        <textarea class="form-control" name="description" id="edit-lesson-description"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="col-form-label">Thời gian:</label>
                        <input type="text" name="start" class="form-control" id="edit-start-time" style="width:100px; display:inline;" placeholder="Bắt đầu">
                        <input type="text" name="end" class="form-control" id="edit-end-time" style="width:100px; display:inline;" placeholder="Kết thúc">
                        <input type="text" name="date" class="form-control" id="edit-date" style="width:150px; display:inline;" placeholder="Ngày">
                    </div>

                    <div class="mb-1">
                        <label class="col-form-label">Giáo viên:</label>
                    </div>
                    <div class="teachers-container scrollbar" style="max-height:100px; overflow: auto;">
                        @foreach($teachers as $teacher)
                        <div class="form-check">
                            <input class="form-check-input add-lesson-teacher" name="teacher_ids[]" type="checkbox" value="{{$teacher->id}}" id="ck-teacher-{{$teacher->id}}">
                            <label class="form-check-label" for="ck-teacher-{{$teacher->id}}">
                                {{$teacher->name}}
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

    function ToDatetime(strDatetime) {
        return new Date(strDatetime);
    }

    function ToTime(strDatetime) {
        var dateTime = new Date(strDatetime);
        var hours = dateTime.getHours();
        var minutes = dateTime.getMinutes();

        var time = hours + ":" + (minutes < 10 ? "0" + minutes : minutes);
        return time;
    }

    function ToDate(strDatetime) {
        var dateTime = new Date(strDatetime);
        var day = dateTime.getDate();
        var month = dateTime.getMonth() + 1;
        var year = dateTime.getFullYear();

        return day + "/" + month + "/" + year;
    }

    flatpickr("#add-date", {
        allowInput: true,
        enableTime: false,
        dateFormat: "d/m/Y",
    });

    flatpickr("#edit-date", {
        allowInput: true,
        enableTime: false,
        dateFormat: "d/m/Y",

    });

    flatpickr("#add-start-time", {
        allowInput: true,
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true
    });

    flatpickr("#add-end-time", {
        allowInput: true,
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true
    });

    flatpickr("#edit-start-time", {
        allowInput: true,
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true
    });

    flatpickr("#edit-end-time", {
        allowInput: true,
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true
    });


    // getdata from server
    $(document).ready(function() {
        $('.edit-button').click(function() {
            var lessonId = $(this).data('id'); // Lấy giá trị ID từ thuộc tính data-id của nút được click
            $('#lessonId').val(lessonId); // Gán giá trị ID vào hidden input
            $.ajax({
                url: '{{ route("get.lesson") }}/' + lessonId,
                type: 'get',
                success: function(response) {
                    $('#edit-lesson-name').val(response.name);
                    $('#edit-lesson-description').val(response.description);
                    $('#edit-date').val(ToDate(response.start_at));
                    $('#edit-start-time').val(ToTime(response.start_at));;
                    $('#edit-end-time').val(ToTime(response.end_at));;
                }
            });

            // teacher lesson
            $.ajax({
                url: '{{ route("get.teacherLesson") }}/' + lessonId,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    $.each(response, function(index, item) {
                        // Duyệt qua từng 
                        idBox = "#ck-teacher-" + item.teacher_id;
                        console.log(idBox);
                        $(idBox).prop("checked", true);

                        // ...
                    });
                }
            });

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

@section('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endsection