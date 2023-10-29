@extends('layouts.app')

@section('header')
@include('elements.header')
@endsection

@section('content')
<div class="container-fluid row">
    <div class="col-md-2">
        <div class="d-flex justify-content-evenly mt-2">
            <a class="text-primary" href="{{route('classLesson',['classId'=>$lesson->classes->id])}}">QL Bài học</a>
            <span class="divider"></span>
            <a class="text-primary" href="{{route('studentInClass',['classId'=>$lesson->classes->id])}}">SV trong lớp</a>
        </div>
        <div class="list-group scrollbar overflow-auto my-2" style="max-height: 400px;">
            <span class="list-group-item list-group-item-action">{{$lesson->classes->name}}</span>
            @foreach($lessons as $lessonItem)
            <a href="{{route('detail.lesson',['id'=>$lessonItem->id])}}" class="list-group-item list-group-item-warning list-group-item-action">{{$lessonItem->name}}</a>
            @endforeach
        </div>
    </div>

    <div class="col-md-10">
        <div class="top-box d-flex justify-content-between my-1" style="width:100%;">
            <h5>Điểm danh: {{$lesson->name}}</h5>
            <div class="search-box" style="width:300px; height:30px">
                <form class="d-flex" action="{{route('detail.lesson',['id'=>$lesson->id])}}" method="get">
                    <input class="form-control me-2" type="text" name="keyword" placeholder="Tìm kiếm" aria-label="Search" value="{{$keyword}}">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </form>
            </div>
            <div class="button-box">
                <button type="button" class="btn btn-primary">Xuất báo cáo</button>
            </div>
        </div>
        <table class="table table-hover table-striped mb-1">
            <thead>
                <tr>
                    <th scope="col" class="text-center">Stt</th>
                    <th scope="col" class="text-center">Tên</th>
                    <th scope="col" class="text-center">Mã SV</th>
                    <th scope="col" class="text-center">Email</th>
                    <th scope="col" class="text-center">Tham gia</th>
                    <th scope="col" class="text-center">Có phép</th>
                    <th scope="col" class="text-center">Không phép</th>
                </tr>
            </thead>

            <tbody>

                @foreach($students as $student)
                <tr>
                    <th scope="row" class="text-center" class="table-Info">{{ $loop->iteration }}</th>
                    <td class="table-Info">{{$student->name}}</td>
                    <td class="table-Info text-center">{{$student->code}}</td>
                    <td class="table-Info">{{$student->email}}</td>
                    <td class="table-Info text-center"><input class="form-check-input check-attend-{{$student->id}}" type="checkbox" @if($student->checkAttendLesson($lesson->id)==1) checked @endif
                        id="select-{{$student->id}}" data-id="{{$student->id}}"></td>

                    <td class="table-Info text-center"><input class="form-check-input check-attend-{{$student->id}}" type="checkbox" @if($student->checkAttendLesson($lesson->id)==2) checked @endif
                        id="ask-{{$student->id}}" data-id="{{$student->id}}"></td>

                    <td class="table-Info text-center"><input class="form-check-input check-attend-{{$student->id}}" type="checkbox" @if($student->checkAttendLesson($lesson->id)==0) checked @endif
                        id="leave-{{$student->id}}" data-id="{{$student->id}}"></td>

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



<script>
    var errorAlert = document.getElementById('error-box');

    // Thêm lớp 'show' để hiển thị div
    errorAlert.classList.add('show');

    // Tự động mờ và biến mất sau 3 giây
    setTimeout(function() {
        errorAlert.classList.remove('show');
    }, 3000);


    // getdata from server
    $(document).ready(function() {

        $('.form-check-input').click(function() {
            var studentId = $(this).data('id'); // Lấy giá trị ID từ thuộc tính data-id của nút được click
            var isChecked = $(this).is(':checked');
            var lessonId = <?= json_encode($lesson->id); ?>;
            $(this).prop('checked', true);
            if (isChecked) {
                console.log('.check-attend-' + studentId);
                $('.check-attend-' + studentId).not(this).prop('checked', false);
                $.ajax({
                    url: '{{ route("attend.lesson")}}/' + lessonId + "/" + studentId,
                    type: 'get',
                    success: function(response) {
                        // AJAX request đã được gửi thành công
                        $('#attend-count').text(response);
                    },
                });
            }

        });

    });
</script>
@endsection

@section('footer')
@include('elements.footer')
@endsection