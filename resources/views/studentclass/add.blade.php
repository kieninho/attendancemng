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
        <h5>Thêm sinh viên vào lớp: {{$class->name}}</h5>
        <div class="search-box" style="width:300px; height:30px">
            <form class="d-flex" action="{{route('add.studentsinclass',['id'=>$class->id])}}" method="get">
                <input class="form-control me-2" type="text" name="keyword" placeholder="Tìm kiếm" aria-label="Search" value="{{$keyword}}">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
            </form>
        </div>
        <div class="button-box">
            <button type="button" id="add-std-btn" class="btn btn-primary"><a class="text-light" href="{{route('add.studentsinclass',['id'=>$class->id])}}">Thêm Nhiều</a></button>
        </div>
    </div>
    <table class="table table-hover table-striped mb-1">
        <thead>
            <tr>
                <th scope="col">Stt</th>
                <th scope="col">Mã SV</th>
                <th scope="col">Tên</th>
                <th scope="col">Email</th>
                <th scope="col">Ngày sinh</th>
                <th scope="col"></th>
                <th scope="col"><input class="form-check-input" type="checkbox" onclick="selectAll()" id="select-all"></th>
            </tr>
        </thead>

        <tbody>

            @foreach($availStudents as $student)
            <tr>
                <th scope="row" class="table-Info">{{ $loop->iteration }}</th>
                <td class="table-Info">{{$student->code}}</td>
                <td class="table-Info">{{$student->name}}</td>
                <td class="table-Info">{{$student->email}}</td>
                <td class="table-Info">{{$student->birthday}}</td>
                <td class="table-Info">
                    <a class="link-primary" href="{{route('store.studentsinclass',['classId'=>$class->id,'studentId'=>$student->id])}}">Thêm vào lớp</a>
                </td>
                <td class="table-Info"><input class="form-check-input" name="item_ids[]" type="checkbox" onclick="setCheckedSelectAll()" id="flexCheckChecked"></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{$availStudents->links()}}
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
<script src="{{asset('js/studentclass/add.js')}}"></script>
@endsection

@section('footer')
@include('elements.footer')
@endsection