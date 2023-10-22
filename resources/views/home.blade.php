@extends('layouts.app')

@section('header')
@include('elements.header')
@endsection

@section('content')
<div class="container-fluid row mt-5">
<div class="col-md-2">
        <div class="list-group scrollbar overflow-auto my-2" style="max-height: 400px;">
            <a href="#" class="list-group-item list-group-item-action">Danh sách lớp</a>
            @foreach($classes as $class)
            <a href="{{route('classLesson',['classId'=>$class->id])}}" class="list-group-item list-group-item-warning list-group-item-action">{{$class->name}}</a>
            @endforeach
        </div>
</div>

    <div class="table-responsive col-md-10">
        <div class="row my-5 mx-3">
            <div class="col-4">
                <a href="{{route('student')}}">
                <div class="card" style="width: 18rem;">
                    <div class="image-container">
                        <img src="{{ asset('images/student.jpg') }}" class="card-img-top" alt="class">
                    </div>
                    <div class="card-body">
                        <p class="card-text text-center"><b class="text-info">{{$countStudent??0}} Sinh Viên</b></p>
                    </div>
                </div>
                </a>
            </div>

            <div class="col-4">
                <a href="{{route('teacher')}}">
                <div class="card" style="width: 18rem;">
                    <div class="image-container">
                        <img src="{{ asset('images/teacher.jpg') }}" class="card-img-top" alt="class">
                    </div>
                    <div class="card-body">
                        <p class="card-text text-center"><b class="text-info">{{$countTeacher??0}} Giáo Viên</b></p>
                    </div>
                </div>
                </a>
            </div>

            <div class="col-4">
                <a href="{{route('class')}}">
                <div class="card" style="width: 18rem;">
                    <div class="image-container">
                        <img src="{{ asset('images/class.jpg') }}" class="card-img-top" alt="class">
                    </div>
                    <div class="card-body">
                        <p class="card-text text-center"><b class="text-info">{{$countClass??0}} Lớp học</b></p>
                    </div>
                </div>
                </a>
            </div>
        </div>
    </div>

</div>

@endsection

@section('footer')
@include('elements.footer')
@endsection