@extends('layouts.app')

@section('header')
@include('elements.header')
@endsection


@section('content')

<div class="container-fluid">
    <div class="top-box d-flex justify-content-between my-1" style="width:100%;">
        <h5>Quản lý giáo viên</h5>
        <div class="search-box" style="width:300px; height:30px">
            <form class="d-flex" action="{{route('teacher')}}" method="get">
                <input class="form-control me-2" type="text" name="keyword" placeholder="Tìm kiếm giáo viên" aria-label="Search" value="{{$keyword}}">
                <button class="btn btn-outline-secondary" type="submit">Tìm</button>
            </form>
        </div>
        <form action="{{route('delete.teachers')}}" method="post">
            @csrf
            <div class="button-box">
                <button type="submit" id="delete-mul" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" class="btn btn-primary" disabled>Xóa nhiều</button>
                <button type="button" id="export" class="btn btn-primary ms-2"><a class="text-light" href="{{route('export.teacher')}}">Xuất Excel</a></button>
                <button type="button" class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#addModal">Thêm GV</button>
            </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-striped mb-1">
            <thead>
                <tr>
                    <th scope="col"><input class="form-check-input" type="checkbox" onclick="selectAll()" id="select-all"></th>
                    <th scope="col">Stt</th>
                    <th scope="col">Tên</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Ngày sinh</th>
                    <th scope="col" class="text-center">Số lớp dạy</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($teachers as $teacher)
                <tr class="td-padding-custom">
                    <td class="table-Info"><input class="form-check-input" name="item_ids[]" value="{{$teacher->id}}" type="checkbox" onclick="setCheckedSelectAll()" id="flexCheckChecked"></td>
                    <td scope="row" class="table-Info">{{ $loop->iteration }}</td>
                    <td class="table-Info">{{$teacher->name}}</td>
                    <td class="table-Info">{{$teacher->email}}</td>
                    <td class="table-Info">{{$teacher->phone}}</td>
                    <td class="table-Info">{{$teacher->birthday}}</td>
                    <td class="table-Info text-center">{{$teacher->countClasses()}}</td>
                    <td class="table-Info">
                        <span class="edit-button text-success cursor-pointer" data-bs-toggle="modal" data-id="{{$teacher->id}}" data-bs-target="#editModal">Sửa</span>
                        <span class="divider"></span>
                        <a class="link-danger" href="{{route('delete.teacher',['id'=>$teacher->id])}}" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
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
        {{$teachers->links()}}

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

<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm Giáo Viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('store.teacher')}}" id="addForm" method="POST">
                @csrf
                <div class="modal-body">

                    <div class="mb-1">
                        <label for="recipient-name" class="col-form-label required-star">Tên giáo viên</label>
                        <input type="text" name="name" placeholder="Tên giáo viên" class="form-control" id="add-teacher-name">
                    </div>

                    <div class="mb-1">
                        <label for="add-teacher-email" class="col-form-label required-star">Email:</label>
                        <input type="email" class="form-control" placeholder="Email" name="email" id="add-teacher-email">
                    </div>

                    <div class="mb-1">
                        <label for="add-phone" class="col-form-label">Điện Thoại</label>
                        <input type="tel" class="form-control" placeholder="Điện thoại" name="phone" id="add-phone" style="width: 200px;">
                    </div>

                    <div class="mb-1">
                        <label for="add-birthday" class="col-form-label">Ngày sinh</label>
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

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sửa Thông Tin Giáo Viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('update.teacher')}}" id="editForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="teacherId" name="teacherId">

                    <div class="mb-1">
                        <label for="edit-teacher-name" class="col-form-label required-star">Tên giáo viên</label>
                        <input type="text" name="name" placeholder="Tên giáo viên" class="form-control" id="edit-teacher-name">
                    </div>


                    <div class="mb-1">
                        <label for="edit-teacher-email" class="col-form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="edit-teacher-email" disabled>
                    </div>

                    <div class="mb-1">
                        <label for="edit-teacher-phone" class="col-form-label">Điện Thoại</label>
                        <input type="tel" class="form-control" placeholder="Điện thoại" name="phone" id="edit-teacher-phone" style="width: 200px;">
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
<div id="get-teacher" data-route="{{ route('get.teacher') }}"></div>


@endsection


@section('scripts-bot')
<script src="{{asset('js/teacher/index.js')}}"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
@endsection

@section('footer')
@include('elements.footer')
@endsection