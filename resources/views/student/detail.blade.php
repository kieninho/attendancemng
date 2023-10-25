@extends('layouts.app')

@section('header')
@include('elements.header')
@endsection

@section('content')
<div class="container-fluid row">

<div class="col-md-2">
        <div class="list-group scrollbar overflow-auto my-2" style="max-height: 400px;">
            <h5 class="list-group-item list-group-item-action">Sinh viên: {{$student->code}}</h5>
            <p class="list-group-item list-group-item-warning list-group-item-action my-0">Tên: {{$student->name}}</p>
            <p class="list-group-item list-group-item-warning list-group-item-action my-0">Email: {{$student->email}}</p>
            <p class="list-group-item list-group-item-warning list-group-item-action my-0">Ngày sinh: {{$student->birthday}}</p>
            <p class="list-group-item list-group-item-warning list-group-item-action my-0">Số lớp đang học: {{$student->classes->count()}}</p>
            <p class="list-group-item list-group-item-warning list-group-item-action my-0">Tỷ lệ chuyên cần: {{intval(($student->lessons->count()??0)/($student->countLessonInClass()??1)*100)}}%{{$student->countLessonInClass()}}</p>
        </div>
    </div>

    <div class="col-md-10">
    <div class="top-box d-flex justify-content-between my-1" style="width:100%;">
        <h5>Danh sách lớp tham gia</h5>
        <div class="search-box" style="width:300px; height:30px">
            <form class="d-flex" action="{{route('detail.student',['id'=>$student->id])}}" method="get">
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
                <th scope="col" class="text-center">Mã Lớp</th>
                <th scope="col" class="text-center">Tên</th>
                <th scope="col" class="text-center">Số buổi</th>
                <th scope="col" class="text-center">Tham gia</th>
                <th scope="col" class="text-center">Vắng</th>
                <th scope="col" class="text-center">Tỷ lệ</th>

            </tr>
        </thead>

        <tbody>

            @foreach($classes as $class)
            <tr>
                <th scope="row" class="table-Info text-center">{{ $loop->iteration }}</th>
                <td class="table-Info text-center"><a href="{{route('classLesson',['classId'=>$class->id])}}">{{$class->code}}</a></td>
                <td class="table-Info">{{$class->name}}</td>
                <?php $countLesson = $class->lessons()->count()??0 ?>
                <td class="table-Info text-center">{{$countLesson}}</td>
                <?php $countLessonAttend = $class->countLessonAttend($student->id)??0 ?>
                <td class="table-Info text-center">{{$countLessonAttend}}</td>
                <td class="table-Info text-center">{{$countLesson - $countLessonAttend}}</td>
                <td class="table-Info text-center">@if($countLesson == 0) {{ 0 }}% @else {{intval($countLessonAttend/$countLesson*100)}}% @endif</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{$classes->links()}}
    </div>

</div>
@endsection

@section('footer')
@include('elements.footer')
@endsection