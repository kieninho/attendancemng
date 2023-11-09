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
            <a class="text-primary" href="{{route('classLesson',['classId'=>$class->id])}}">Buổi học</a>
        </div>
        <div class="list-group scrollbar overflow-auto my-2" style="max-height: 400px;">
            <span class="list-group-item list-group-item-action">Quản lý sinh viên trong lớp</span>
            @foreach($classes as $classItem)
            <a href="{{route('studentInClass',['classId'=>$classItem->id])}}" class="list-group-item list-group-item-light list-group-item-action">{{$classItem->name}}</a>
            @endforeach
        </div>
    </div>

    <div class="col-md-10">
        <div class="top-box d-flex justify-content-between my-1" style="width:100%;">
            <h5>Danh sách sinh viên lớp: {{$class->name}}</h5>
            <div class="search-box" style="width:300px; height:30px">
                <form class="d-flex" action="{{route('studentInClass',['classId'=>$class->id])}}" method="get">
                    <input class="form-control me-2" type="text" name="keyword" placeholder="Tìm kiếm" aria-label="Search" value="{{$keyword}}">
                    <button class="btn btn-outline-secondary" type="submit">Tìm</button>
                </form>
            </div>
            <div class="button-box">
                <form action="{{route('deleteMulti.studentInClass',['classId'=>$class->id])}}" method="post">
                    @csrf
                    <button type="submit" id="delete-mul" class="btn btn-primary" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" disabled>Xóa nhiều</button>
                    <button type="button" id="export" class="btn btn-primary ms-2"><a class="text-light" href="{{route('export.studentInClass',['classId'=>$class->id])}}">Xuất Excel</a></button>
                    <button type="button" id="add-std-btn" class="btn btn-primary ms-2"><a class="text-light" href="{{route('add.studentsinclass',['id'=>$class->id])}}">Thêm SV</a></button>
            </div>
        </div>
        <table class="table table-hover table-striped mb-1">
            <thead>
                <tr>
                    <th scope="col"><input class="form-check-input" type="checkbox" onclick="selectAll()" id="select-all"></th>
                    <th scope="col">Stt</th>
                    <th scope="col">Mã SV</th>
                    <th scope="col">Tên</th>
                    <th scope="col">Email</th>
                    <th scope="col">Vào lớp</th>
                    <th scope="col" class="text-center">Chuyên cần</th>
                    <th scope="col"></th>
                </tr>
            </thead>

            <tbody>

                @forelse($students as $student)
                <tr class="td-padding-custom">
                    <td class="table-Info"><input class="form-check-input" name="item_ids[]" value="{{$student->id}}" type="checkbox" onclick="setCheckedSelectAll()" id="flexCheckChecked"></td>
                    <td scope="row" class="table-Info">{{ $loop->iteration }}</td>
                    <td class="table-Info">{{$student->code}}</td>
                    <td class="table-Info">{{$student->name}}</td>
                    <td class="table-Info">{{$student->email}}</td>
                    <td class="table-Info">{{$student->getJoinDate($class->id)}}</td>
                    <td class="table-Info text-center">{{$student->classAttendRate($class->id)}}%</td>
                    <td class="table-Info">
                        <a class="link-danger" href="{{route('delete.studentInClass',['classId'=>$class->id,'studentId'=>$student->id])}}" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                        <span class="divider"></span>
                        <a class="link-primary" href="{{route('detail.student',['id'=>$student->id])}}">Chi tiết</a>
                    </td>
                </tr>
                @empty
                <tr class="td-padding-custom">
                    <td colspan="8" class="text-center">Không có dữ liệu</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </form>
        {{$students->links()}}
    </div>

</div>

<div id="error-box" class="position-fixed bottom-0 end-0 p-3 fade" role="alert" style="z-index: 9999;">
    @if ($errors->any())
    <div class="alert alert-danger px-2 py-1">
        <ul class="ps-1">
            @foreach ($errors->all() as $error)
            <li class="text-mess" style="list-style-type:none;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>


@endsection

@section('scripts-bot')
<script src="{{asset('js/studentclass/index.js')}}"></script>
@endsection

@section('footer')
@include('elements.footer')
@endsection