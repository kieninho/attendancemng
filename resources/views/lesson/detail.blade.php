@extends('layouts.app')

@section('header')
@include('elements.header')
@endsection

@section('content')
<div class="container-fluid row">
    <div class="col-md-2">
        <div class="list-group scrollbar overflow-auto my-2" style="max-height: 400px;">
            <a href="#" class="list-group-item list-group-item-action">{{$lesson->classes->name}} - Danh sách buổi học</a>
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
                    <th scope="col" class="text-center">Tham gia <span id="attend-count">{{$lesson->students->count()??0}}</span>/{{$lesson->classes->students->count()}}</th>
                </tr>
            </thead>

            <tbody>

                @foreach($students as $student)
                <tr>
                    <th scope="row" class="text-center" class="table-Info">{{ $loop->iteration }}</th>
                    <td class="table-Info">{{$student->name}}</td>
                    <td class="table-Info text-center">{{$student->code}}</td>
                    <td class="table-Info">{{$student->email}}</td>
                    <td class="table-Info"><input class="form-check-input check-attend" type="checkbox" 
                    @if(!$student->lessons()->wherePivot('lesson_id', $lesson->id)->get()->isEmpty()) checked @endif 
                    id="select-{{$student->id}}" data-id="{{$student->id}}"></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{$students->links()}}

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
        $('.check-attend').click(function() {
            var isChecked = $(this).is(':checked');
            var studentId = $(this).data('id'); // Lấy giá trị ID từ thuộc tính data-id của nút được click
            var lessonId = <?= json_encode($lesson->id); ?>;
            console.log(studentId);
            console.log('{{ route("attend.lesson")}}/' + lessonId + "/" + studentId, );
            if (isChecked) {
                $.ajax({
                    url: '{{ route("attend.lesson")}}/' + lessonId + "/" + studentId,
                    type: 'get',
                    success: function(response) {
                        // AJAX request đã được gửi thành công
                        $('#attend-count').text(response);
                    },
                });
            } else {
                $.ajax({
                    url: '{{ route("leave.lesson")}}/' + lessonId + "/" + studentId,
                    type: 'get',
                    success: function(response) {
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

@section('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endsection