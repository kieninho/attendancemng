@extends('layouts.app')

@section('header')
@include('elements.header')
@endsection


@section('content')

<div class="container-fluid">
    <div class="top-box d-flex justify-content-between my-1" style="width:100%; height:24px">
        <h5>Quản lý người dùng</h5>
        <div class="search-box" style="width:300px; height:30px">
            <form class="d-flex" action="{{route('user')}}" method="get">
                <input class="form-control me-2" type="text" name="keyword" placeholder="Tìm kiếm" aria-label="Search" value="{{$keyword}}">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
            </form>
        </div>
        <div class="button-box">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Thêm User</button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-striped mb-1">
            <thead>
                <tr>
                    <th scope="col">Stt</th>
                    <th scope="col">Tên</th>
                    <th scope="col">Email</th>
                    <th scope="col">Ngày tạo</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <th scope="row" class="table-Info">{{ $loop->iteration }}</th>
                    <td class="table-Info">{{$user->name}}</td>
                    <td class="table-Info">{{$user->email}}</td>
                    <td class="table-Info">{{$user->created_at}}</td>
                    <td class="table-Info">
                        <span class="edit-button text-success cursor-pointer" data-bs-toggle="modal" data-id="{{$user->id}}" data-bs-target="#editModal">Sửa</span>
                        <span class="divider"></span>
                        <a class="link-danger" href="{{route('delete.user',['id'=>$user->id])}}">Xóa</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{$users->links()}}
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

<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('store.user')}}" method="POST">
                @csrf
                <div class="modal-body">

                    <div class="mb-1">
                        <label for="recipient-name" class="col-form-label">Tên:</label>
                        <input type="text" name="name" class="form-control" id="add-user-name">
                    </div>

                    <div class="mb-1">
                        <label for="add-user-email" class="col-form-label">Email:</label>
                        <input type="email" class="form-control" name="email" id="add-user-email">
                    </div>


                    <div class="mb-1">
                        <label for="password" class="col-form-label">Mật khẩu:</label>
                        <input type="password" class="form-control" name="password" id="password">
                    </div>

                    <div class="mb-1">
                        <label for="password2" class="col-form-label">Nhập lại mật khẩu:</label>
                        <input type="password" class="form-control" name="password2" id="password2">
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
                <h5 class="modal-title">Sửa Thông Tin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('update.user')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="userId" name="userId" value="">

                    <div class="mb-1">
                        <label for="edit-user-name" class="col-form-label">Tên:</label>
                        <input type="text" name="name" class="form-control" id="edit-user-name">
                    </div>

                    <div class="mb-1">
                        <label for="edit-user-email" class="col-form-label">Email:</label>
                        <input type="email" class="form-control" name="email" id="edit-user-email" disabled>
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

<script>
    // Hiện thị lỗi
    var errorAlert = document.getElementById('error-box');

    // Thêm lớp 'show' để hiển thị div
    errorAlert.classList.add('show');

    // Tự động mờ và biến mất sau 3 giây
    setTimeout(function() {
        errorAlert.classList.remove('show');
    }, 3000);


    $(document).ready(function() {
        $('.edit-button').click(function() {
            var userId = $(this).data('id'); // Lấy giá trị ID từ thuộc tính data-id của nút được click
            $('#userId').val(userId); // Gán giá trị ID vào hidden input
            $.ajax({
                url: '{{ route("get.user") }}/' + userId,
                type: 'get',
                success: function(response) {
                    $('#edit-user-name').val(response.name);
                    $('#edit-user-email').val(response.email);
                    $('#userId').val(response.id);
                }
            });
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