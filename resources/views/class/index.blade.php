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
                <button class="btn btn-outline-secondary" type="submit">Search</button>
            </form>
        </div>
        <div class="button-box">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Tạo Lớp</button>
            <button type="button" class="btn btn-primary">Xóa lớp đã chọn</button>
        </div>
    </div>
    <div class="table-responsive">

        <table class="table table-hover table-striped mb-1">
            <thead>
                <tr>
                    <th scope="col" class="text-center">Stt</th>
                    <th scope="col" class="text-center">Mã lớp</th>
                    <th scope="col" class="text-center">Tên</th>
                    <th scope="col" class="text-center">Mô tả</th>
                    <th scope="col" class="text-center">Sinh viên</th>
                    <th scope="col" class="text-center">Buổi học</th>
                    <th scope="col" class="text-center">Ngày tạo</th>
                    <th scope="col"></th>
                    <th scope="col"><input class="form-check-input" type="checkbox" onclick="selectAll()" id="select-all"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($classes as $class)
                <tr>
                    <th scope="row" class="table-Info text-center">{{ $loop->iteration }}</th>
                    <td class="table-Info text-center">{{$class->code}}</td>
                    <td class="table-Info">{{$class->name}}</td>
                    <td class="table-Info">{{$class->description}}</td>
                    <td class="table-Info text-center">{{$class->students->count()??0}}</td>
                    <td class="table-Info text-center">{{$class->lessons->count()??0}}</td>
                    <td class="table-Info text-center">{{$class->created_at}}</td>
                    <td class="table-Info text-center">
                        <span class="edit-button text-success cursor-pointer" data-bs-toggle="modal" data-id="{{$class->id}}" data-bs-target="#editModal">Sửa</span> <span class="divider"></span>
                        <a class="link-danger" href="{{route('delete.class',['id'=>$class->id])}}">Xóa</a> <span class="divider"></span>
                        <a class="link-primary" href="{{route('classLesson',['classId'=>$class->id])}}">Buổi học</a> <span class="divider"></span>
                        <a class="link-dark" href="{{route('studentInClass',['classId'=>$class->id])}}">Sinh viên</a>
                    </td>
                    <td class="table-Info"><input class="form-check-input" name="item_ids[]" type="checkbox" onclick="setCheckedSelectAll()" id="flexCheckChecked"></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{$classes->links()}}
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
                        <label for="recipient-name" class="col-form-label">Tên lớp:</label>
                        <input type="text" name="name" class="form-control" id="add-class-name">
                        @error('name')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-1">
                        <label for="description-class-name" class="col-form-label">Chi tiết:</label>
                        <textarea class="form-control" name="description" id="description-class-name"></textarea>
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
                <h5 class="modal-title">Tạo lớp</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('update.class')}}" method="POST" id="editForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="classId" name="classId">
                    <div class="mb-1">
                        <label for="recipient-name" class="col-form-label">Tên lớp:</label>
                        <input type="text" name="name" class="form-control" id="edit-class-name">
                        @error('name')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-1">
                        <label for="description-class-name" name="name" class="col-form-label">Chi tiết:</label>
                        <textarea class="form-control" name="description" id="edit-class-description"></textarea>
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
<script src="{{asset('js/class/index.js')}}"></script>
<script>
    $(document).ready(function () {
    $('.edit-button').click(function () {
        var classId = $(this).data('id'); // Lấy giá trị ID từ thuộc tính data-id của nút được click
        $('#classId').val(classId); // Gán giá trị ID vào hidden input
        console.log(classId);

        $.ajax({
            url: '{{ route("get.class") }}/' + classId,
            type: 'get',
            success: function (response) {
                $('#edit-class-name').val(response.name);
                $('#edit-class-description').val(response.description);
            }
        });
    });
});

$('#addModal').on('hidden.bs.modal', function () {
    $('#addForm')[0].reset();
});

$('#editModal').on('hidden.bs.modal', function () {
    $('#editForm')[0].reset();
});
</script>
@endsection


@section('footer')
@include('elements.footer')
@endsection