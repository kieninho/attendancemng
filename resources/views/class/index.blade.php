@extends('layouts.app')

@section('header')
@include('elements.header')
@endsection

@section('content')
<div class="container-fluid">
    <div class="top-box d-flex justify-content-between my-1" style="width:100%;">
        <h5>Quản lý lớp</h5>
        <div class="search-box" style="width:300px; height:30px">
            <form class="d-flex" action="{{route('class')}}" method="get">
                <input class="form-control me-2" type="text" name="keyword" placeholder="Tìm kiếm lớp" aria-label="Search" value="{{$keyword}}">
                <button class="btn btn-outline-secondary" type="submit">Tìm</button>
            </form>
        </div>
        <div class="button-box">
            <button type="button" id="delete-mul" class="btn btn-primary" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" disabled>Xóa nhiều</button>
            <button type="button" id="export" class="btn btn-primary ms-2"><a class="text-light" href="{{route('export.class')}}">Xuất Excel</a></button>
            <button type="button" class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#addModal">Tạo Lớp</button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-striped mb-1">
            <thead>
                <tr>
                    <th scope="col" class="text-center"><input class="form-check-input" type="checkbox" onclick="selectAll()" id="select-all"></th>
                    <th scope="col" class="text-center">Stt</th>
                    <th scope="col" class="text-center">Mã lớp</th>
                    <th scope="col" class="text-center">Tên</th>
                    <th scope="col" class="text-center">Mô tả</th>
                    <th scope="col" class="text-center">Sinh viên</th>
                    <th scope="col" class="text-center">Giáo viên</th>
                    <th scope="col" class="text-center">Buổi học</th>
                    <th scope="col" class="text-center">Chuyên cần</th>
                    <th scope="col" class="text-center">Khai giảng</th>
                    <th scope="col" class="text-center">Kết thúc</th>
                    <th scope="col" class="text-center">Trạng thái</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($classes as $class)
                <tr class="td-padding-custom">
                    <td class="table-Info text-center"><input class="form-check-input" name="item_ids[]" type="checkbox" onclick="setCheckedSelectAll()" id="flexCheckChecked"></td>
                    <td scope="row" class="table-Info text-center">{{ $loop->iteration }}</td>
                    <td class="table-Info text-center">{{$class->code}}</td>
                    <td class="table-Info">{{$class->name}}</td>
                    <td class="table-Info">{{$class->description}}</td>
                    <td class="table-Info text-center">{{$class->countStudent()}}</td>
                    <td class="table-Info text-center">{{$class->getTeachersStringByClass()}}</td>
                    <td class="table-Info text-center">{{$class->countLesson()}}</td>
                    <td class="table-Info text-center">{{$class->getAverageAttendance()}}%</td>
                    <td class="table-Info text-center">{{$class->startDay()}}</td>
                    <td class="table-Info text-center">{{$class->endDay()}}</td>
                    <td class="table-Info text-center">{{$class->getStatus()}}

                    </td>
                    <td class="table-Info text-center">
                        <span class="edit-button text-success cursor-pointer" data-bs-toggle="modal" data-id="{{$class->id}}" data-bs-target="#editModal">Sửa</span> <span class="divider"></span>
                        <a class="link-danger" href="{{route('delete.class',['id'=>$class->id])}}" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a> <span class="divider"></span>
                        <a class="link-primary" href="{{route('classLesson',['classId'=>$class->id])}}">Buổi học</a> <span class="divider"></span>
                        <a class="link-dark" href="{{route('studentInClass',['classId'=>$class->id])}}">SV</a>
                    </td>
                </tr>
                @empty
                <tr class="td-padding-custom">
                    <td colspan="13" class="text-center">Không có dữ liệu</td>
                </tr>
                @endforelse
    </div>
    </tbody>
    </table>
    {{$classes->links()}}
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
                <h5 class="modal-title">Tạo lớp</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('store.class')}}" method="POST" id="addForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-1">
                        <label for="recipient-name" class="col-form-label required-star">Tên lớp</label>
                        <input type="text" name="name" class="form-control" id="add-class-name" placeholder="Tên lớp">
                        <div class="alert alert-danger mt-2">
                            <p id="add-name-err"></p>
                        </div>
                    </div>
                    <div class="mb-1">
                        <label for="description-class-name" class="col-form-label">Chi tiết</label>
                        <textarea class="form-control" name="description" id="description-class-name" placeholder="Chi tiết"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" id="addBtn">Thêm mới</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chỉnh sửa lớp</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('update.class')}}" method="POST" id="editForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="classId" name="classId">
                    <div class="mb-1">
                        <label for="recipient-name" class="col-form-label required-star">Tên lớp</label>
                        <input type="text" name="name" class="form-control" id="edit-class-name" placeholder="Tên lớp">
                    </div>
                    <div class="mb-1">
                        <label for="description-class-name" name="name" class="col-form-label">Chi tiết</label>
                        <textarea class="form-control" name="description" id="edit-class-description" placeholder="Chi tiết"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" id="editBtn">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="get-class" data-route="{{ route('get.class') }}"></div>

@endsection

@section('scripts-bot')
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="{{asset('js/class/index.js')}}"></script>
@endsection


@section('footer')
@include('elements.footer')
@endsection