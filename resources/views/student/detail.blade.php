@extends('layouts.app')

@section('header')
@include('elements.header')
@endsection

@section('content')
<div class="container-fluid row">

<div class="col-md-2">
    <div class="d-flex justify-content-evenly mt-2">
            <a class="text-primary" href="{{route('student')}}">Danh sách sinh viên</a>
    </div>
        <div class="list-group scrollbar overflow-auto my-2" style="max-height: 400px;">
            <span class="list-group-item list-group-item-action">Sinh viên: {{$student->code}}</span>
            <p class="list-group-item list-group-item-light list-group-item-action my-0">Tên: {{$student->name}}</p>
            <p class="list-group-item list-group-item-light list-group-item-action my-0">Email: {{$student->email}}</p>
            <p class="list-group-item list-group-item-light list-group-item-action my-0">Ngày sinh: {{$student->birthday}}</p>
            <p class="list-group-item list-group-item-light list-group-item-action my-0">Số lớp đang học: {{$student->classes->count()}}</p>
            <p class="list-group-item list-group-item-light list-group-item-action my-0">Tỷ lệ chuyên cần: {{$student->attendRate()}}%</p>
        </div>
    </div>

    <div class="col-md-10">
    <div class="top-box d-flex justify-content-between my-1" style="width:100%;">
        <h5>Danh sách lớp tham gia</h5>
        <div class="search-box" style="width:300px; height:30px">
            <form class="d-flex" action="{{route('detail.student',['id'=>$student->id])}}" method="get">
                <input class="form-control me-2" type="text" name="keyword" placeholder="Tìm kiếm" aria-label="Search" value="{{$keyword}}">
                <button class="btn btn-outline-secondary" type="submit">Tìm</button>
            </form>
        </div>
        <div class="button-box">
            <button type="button" class="btn btn-primary"><a class="text-light" href="{{route('exportDetail.student',['id'=>$student->id])}}">Xuất Excel</a></button>
        </div>
    </div>
    <table class="table table-hover table-striped mb-1">
        <thead>
            <tr>
                <th scope="col" class="text-center">Stt</th>
                <th scope="col" class="text-center">Mã Lớp</th>
                <th scope="col" class="text-center">Tên</th>
                <th scope="col" class="text-center">Số buổi</th>
                <th scope="col" class="text-center">Chuyên cần</th>
            </tr>
        </thead>

        <tbody>

            @forelse($classes as $class)
            <tr class="td-padding-custom">
                <td scope="row" class="table-Info text-center">{{ $loop->iteration }}</td>
                <td class="table-Info text-center"><a href="{{route('classLesson',['classId'=>$class->id])}}">{{$class->code}}</a></td>
                <td class="table-Info">{{$class->name}}</td>
                <td class="table-Info text-center">{{$attend = $class->countAttendWithStudentId($student->id)}}</td>
                @php
                $all = $class->countLessonWithStudentId($student->id);
                $attend = $class->countAttendWithStudentId($student->id);
                
                @endphp
                <td class="table-Info text-center">@if($attend == 0) {{0}}% @else {{round($attend/$all*100)}}%  @endif </td>
            </tr>
            @empty
                <tr class="td-padding-custom">
                    <td colspan="5" class="text-center">Không có dữ liệu</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{$classes->links()}}
    </div>

</div>
@endsection

@section('footer')
@include('elements.footer')
@endsection