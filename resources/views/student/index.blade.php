@extends('layouts.app')

@section('header')
@include('elements.header')
@endsection


@section('content')
<div class="container-fluid">
    <div class="top-box d-flex justify-content-between my-1" style="width:100%;">
        <h5>Quản lý sinh viên</h5>
        <div class="search-box" style="width:300px; height:30px">
            <form class="d-flex" action="{{route('student')}}" method="get">
                <input class="form-control me-2" type="text" name="keyword" placeholder="Tìm kiếm sinh viên" aria-label="Search" value="{{$keyword}}">
                <button class="btn btn-outline-secondary" type="submit">Tìm</button>
            </form>
        </div>
        <div class="button-box">
        <form action="{{route('deleteMulti.student')}}" method="post">
                    @csrf
            <button type="submit" class="btn btn-primary" id="delete-mul" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" disabled>Xóa nhiều</button>
            <button type="button" id="export" class="btn btn-primary ms-2"><a class="text-light" href="{{route('export.student')}}">Xuất Excel</a></button>
            <button type="button" class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#addModal">Thêm SV</button>
        </div>
    </div>
    <div class="table-responsive">

        <table class="table table-hover table-striped mb-1">
            <thead>
                <tr>
                    <th scope="col"><input class="form-check-input" type="checkbox" onclick="selectAll()" id="select-all"></th>
                    <th scope="col" class="text-center">Stt</th>
                    <th scope="col" class="text-center">Mã SV</th>
                    <th scope="col" class="text-center">Tên</th>
                    <th scope="col" class="text-center">Email</th>
                    <th scope="col" class="text-center">Ngày sinh</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                <tr>
                    <td class="table-Info"><input class="form-check-input" name="item_ids[]" value="{{$student->id}}" type="checkbox" onclick="setCheckedSelectAll()" id="flexCheckChecked"></td>
                    <th scope="row" class="table-Info text-center">{{ $loop->iteration }}</th>
                    <td class="table-Info text-center">{{$student->code}}</td>
                    <td class="table-Info">{{$student->name}}</td>
                    <td class="table-Info">{{$student->email}}</td>
                    <td class="table-Info text-center">{{$student->birthday}}</td>
                    <td class="table-Info">
                        <span class="edit-button text-success cursor-pointer" data-bs-toggle="modal" data-id="{{$student->id}}" data-bs-target="#editModal">Sửa</span>
                        <span class="divider"></span>
                        <a class="link-danger" href="{{route('delete.student',['id'=>$student->id])}}" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                        <span class="divider"></span>
                        <a class="link-primary" href="{{route('detail.student',['id'=>$student->id])}}">Chi tiết</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Không có dữ liệu</td>
                </tr>
                @endforelse
                </form>
            </tbody>
        </table>
        {{$students->links()}}
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
</div>




<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm Sinh Viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addForm" action="{{route('store.student')}}" method="POST">
                @csrf
                <div class="modal-body">

                    <div class="mb-1">
                        <label for="recipient-name" class="col-form-label required-star">Tên sinh viên</label>
                        <input type="text" name="name" class="form-control" placeholder="Tên sinh viên" id="add-student-name">
                    </div>
                    <div class="mb-1">
                        <label for="add-student-email" class="col-form-label required-star">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Email" id="add-student-email">
                    </div>

                    <div class="mb-1">
                        <label for="add-birthday" class="col-form-label">Ngày sinh:</label>
                        <input type="date" class="form-control" name="birthday" id="add-birthday" style="width: 150px;">
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
                <h5 class="modal-title">Sửa Thông Tin Sinh Viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" action="{{route('update.student')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="studentId" name="studentId">

                    <div class="mb-1">
                        <label for="edit-student-code" class="col-form-label">Mã SV:</label>
                        <input type="text" name="code" class="form-control" id="edit-student-code" style="width: 150px;" disabled>
                    </div>

                    <div class="mb-1">
                        <label for="edit-student-name" class="col-form-label required-star">Tên sinh viên</label>
                        <input type="text" name="name" placeholder="Tên sinh viên" class="form-control" id="edit-student-name">
                    </div>
                    <div class="mb-1">
                        <label for="edit-student-email" class="col-form-label required-star">Email</label>
                        <input type="email" class="form-control" placeholder="Email" name="email" id="edit-student-email">
                    </div>

                    <div class="mb-1">
                        <label for="edit-birthday" class="col-form-label">Ngày sinh:</label>
                        <input type="date" class="form-control" name="birthday" id="edit-birthday" style="width: 150px;">
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

<div id="get-student" data-route="{{ route('get.student') }}"></div>


@endsection

@section('scripts-bot')
<script src="{{asset('js/student/index.js')}}"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
@endsection

@section('footer')
@include('elements.footer')
@endsection