@extends('layouts.app')

@section('header')
@include('elements.header')
@endsection

@section('content')
<div class="container-fluid row">
    <div class="col-md-2">
        <div class="d-flex justify-content-evenly mt-2">
            <a class="text-primary" href="{{route('class')}}">DS lớp</a>
            <span class="divider"></span>
            <a class="text-primary" href="{{route('studentInClass',['classId'=>$class->id])}}">Sinh viên</a>
        </div>
        <div class="list-group scrollbar overflow-auto my-2" style="max-height: 400px;">
            <span class="list-group-item list-group-item-action">Quản lý bài học</span>
            @foreach($classes as $classItem)
            <a href="{{route('classLesson',['classId'=>$classItem->id])}}" class="list-group-item list-group-item-light list-group-item-action">{{$classItem->name}}</a>
            @endforeach
        </div>
    </div>

    <div class="col-md-10">
        <div class="top-box d-flex justify-content-between my-1" style="width:100%;">
            <h5>Danh sách bài học lớp: {{$class->name}}</h5>
            <div class="search-box" style="width:300px; height:30px">
                <form class="d-flex" action="{{route('classLesson',['classId'=>$class->id])}}" method="get">
                    <input class="form-control me-2" type="text" name="keyword" placeholder="Tìm kiếm" aria-label="Search" value="{{$keyword}}">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </form>
            </div>
            <div class="button-box">
                <button type="submit" class="btn btn-primary" id="delete-mul" disabled>Xóa nhiều</input>
                    <button type="button" id="export" class="btn btn-primary  ms-2">Xuất Excel</button>
                    <button type="button" class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#addModal">Thêm bài học</button>
            </div>
        </div>
        <table class="table table-hover table-striped mb-1">
            <thead>
                <tr>
                    <th scope="col" class="text-center">Stt</th>
                    <th scope="col" class="text-center">Tên</th>
                    <th scope="col" class="text-center">Mô tả</th>
                    <th scope="col" class="text-center">Thời gian</th>
                    <th scope="col" class="text-center">GV</th>
                    <th scope="col" class="text-center">Sĩ số</th>
                    <th scope="col" class="text-center">Chuyên cần</th>
                    <th scope="col"></th>
                    <th scope="col" class="text-center" scope="col"><input class="form-check-input" type="checkbox" onclick="selectAll()" id="select-all"></th>
                </tr>
            </thead>

            <tbody>

                @foreach($lessons as $lesson)
                <tr @if($lesson->start_at > now()) class="un-available"
                    @elseif($lesson->checkedAttendance())
                    class="row-checked"
                    @elseif(!$lesson->checkedAttendance())
                    class="row-unchecked"
                @endif >
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
                    <td class="table-Info text-center">{{$lesson->countNumberAttend()}}/{{$lesson->countStudent()}}</td>
                    <td class="table-Info text-center">{{$lesson->getAttendRate()}}%</td>
                    <td class="table-Info text-center">
                        <span class="edit-button text-success cursor-pointer" data-bs-toggle="modal" data-id="{{$lesson->id}}" data-bs-target="#editModal">Sửa</span>
                        <span class="divider"></span>
                        <a class="link-danger" href="{{route('delete.lesson',['id'=>$lesson->id])}}">Xóa</a>
                        <span class="divider"></span>
                        <a class="link-primary @if($lesson->start_at > now()) disabled-link @endif" href="{{route('detail.lesson',['id'=>$lesson->id])}}">Điểm danh</a>
                    </td>
                    <td class="table-Info text-center"><input class="form-check-input" name="item_ids[]" type="checkbox" onclick="setCheckedSelectAll()" id="flexCheckChecked"></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{$lessons->links()}}
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

<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tạo Bài học</h5>
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
                        <br>
                        <input type="time" name="start" class="form-control" id="add-start-time" style="width:150px; display:inline;" placeholder="Bắt đầu">
                        <input type="time" name="end" class="form-control" id="add-end-time" style="width:150px; display:inline;" placeholder="Kết thúc">
                        <input type="date" name="date" class="form-control" id="add-date" style="width:150px; display:inline;" placeholder="Ngày">
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
                        <br>
                        <input type="time" name="start" class="form-control" id="edit-start-time" style="width:150px; display:inline;" placeholder="Bắt đầu">
                        <input type="time" name="end" class="form-control" id="edit-end-time" style="width:150px; display:inline;" placeholder="Kết thúc">
                        <input type="date" name="date" class="form-control" id="edit-date" style="width:150px; display:inline;" placeholder="Ngày">
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
<script src="{{asset('js/lesson/classlesson.js')}}"></script>

<script>
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

                    $('#edit-start-time').val(getTimeFromString(response.start_at));
                    $('#edit-end-time').val(getTimeFromString(response.end_at));
                    $('#edit-date').val(getDateFromString(response.start_at));
                }
            });

            // teacher lesson
            $.ajax({
                url: '{{ route("get.teacherLesson") }}/' + lessonId,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    $.each(response, function(index, item) {
                        // Duyệt qua từng bản ghi
                        idBox = "#ck-teacher-" + item.teacher_id;
                        $(idBox).prop("checked", true);
                    });
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