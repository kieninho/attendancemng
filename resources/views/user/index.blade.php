@extends('layouts.app')

@section('header')
@include('elements.header')
@endsection


@section('content')

<div class="container-fluid">
    <div class="top-box d-flex justify-content-between my-1" style="width:100%;">
        <h5>Quản lý Admin</h5>
        <div class="search-box" style="width:300px; height:30px">
            <form class="d-flex" action="{{route('user')}}" method="get">
                <input class="form-control me-2" type="text" name="keyword" placeholder="Tìm kiếm" aria-label="Search" value="{{$keyword}}">
                <button class="btn btn-outline-secondary" type="submit">Tìm</button>
            </form>
        </div>
        <div class="button-box">
            <button type="button" id="export" class="btn btn-primary ms-2"><a class="text-light" href="{{route('export.user')}}">Xuất Excel</a></button>
            <button type="button" class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#addModal">Thêm QTV</button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-striped mb-1">
            <thead>
                <tr>
                    <th scope="col" class="text-center">Stt</th>
                    <th scope="col" class="text-center">Tên</th>
                    <th scope="col" class="text-center">Email</th>
                    <th scope="col" class="text-center">Ngày tạo</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <th scope="row" class="table-Info text-center">{{ $loop->iteration }}</th>
                    <td class="table-Info">{{$user->name}}</td>
                    <td class="table-Info">{{$user->email}}</td>
                    <td class="table-Info text-center">{{$user->created_at}}</td>
                    <td class="table-Info text-center">
                        <span class="edit-button text-success cursor-pointer" data-bs-toggle="modal" data-id="{{$user->id}}" data-bs-target="#editModal">Sửa</span>
                        <span class="divider"></span>
                        <a class="link-danger" href="{{route('delete.user',['id'=>$user->id])}}" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                        <span class="divider"></span>
                        <span class="reset-pass-button text-primary cursor-pointer" data-bs-toggle="modal" data-id="{{$user->id}}" data-bs-target="#resetPasswordModal">Đổi mật khẩu</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Không có dữ liệu</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{$users->links()}}
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
                <h5 class="modal-title">Thêm QTV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addForm" action="{{route('store.user')}}" method="POST">
                @csrf
                <div class="modal-body">

                    <div class="mb-1">
                        <label for="recipient-name" class="col-form-label required-star">Tên</label>
                        <input type="text" name="name" placeholder="Tên user" class="form-control" id="add-user-name">
                    </div>

                    <div class="mb-1">
                        <label for="add-user-email" class="col-form-label required-star">Email</label>
                        <input type="email" class="form-control" placeholder="Email" name="email" id="add-user-email">
                    </div>


                    <div class="mb-1">
                        <label for="password" class="col-form-label required-star">Mật khẩu</label>
                        <input type="password" class="form-control" placeholder="Mật khẩu" name="password" id="password">
                    </div>

                    <div class="mb-1">
                        <label for="password2" class="col-form-label required-star">Nhập lại mật khẩu</label>
                        <input type="password" class="form-control" placeholder="Xác nhận mật khẩu" name="password2" id="password2">
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
            <form id="editForm" action="{{route('update.user')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="userId" name="userId" value="">

                    <div class="mb-1">
                        <label for="edit-user-name" class="col-form-label required-star">Tên</label>
                        <input type="text" name="name" placeholder="Tên user" class="form-control" id="edit-user-name">
                    </div>

                    <div class="mb-1">
                        <label for="edit-user-email" class="col-form-label required-star">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Email" id="edit-user-email" disabled>
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

<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Đổi mật khẩu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="resetPasswordForm" action="{{route('resetPassword.user')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="userIdReset" name="id" value="">

                    <div class="mb-1">
                       <h5>User: <span id="email-text"></span></h5>
                    </div>

                    <div class="mb-1">
                        <label for="new-pass" class="col-form-label required-star">Mật khẩu mới</label>
                        <input type="password" class="form-control" name="newpass" placeholder="Mật khẩu mới" id="new-pass">
                    </div>

                    <div class="mb-1">
                        <label for="new-pass-2" class="col-form-label required-star">Nhập lại mật khẩu</label>
                        <input type="password" class="form-control" name="newpass2" placeholder="Xác nhận mật khẩu" id="new-pass-2">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="get-user" data-route="{{ route('get.user') }}"></div>


@endsection

@section('scripts-bot')
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="{{asset('js/user/index.js')}}"></script>
@endsection


@section('footer')
@include('elements.footer')
@endsection