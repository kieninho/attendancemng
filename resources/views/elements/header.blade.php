@php
use Illuminate\Support\Facades\Auth;
@endphp
<header>
  <div class="px-3 text-bg-secondary bg-secondary border-bottom">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="/" class="d-flex align-items-center my-2 my-lg-0 me-lg-auto text-white text-decoration-none">
          <img class="bi me-2" width="60" height="48" src="{{ asset('images/logo.png') }}">
        </a>

        <ul class="nav col-10 col-lg-auto my-2 justify-content-center my-md-0 text-small">
          <li>
            <a href="{{route('home')}}" class="nav-link text-white">
              <img class="bi d-block mx-auto mb-1" width="24" height="24" src="{{ asset('images/home.svg') }}">
              Trang chủ
            </a>
          </li>
          <li>
            <a href="{{route('class')}}" class="nav-link text-white">
              <img class="bi d-block mx-auto mb-1" width="24" height="24" src="{{ asset('images/class.svg') }}">
              Lớp
            </a>
          </li>
          <li>
            <a href="{{route('student')}}" class="nav-link text-white">
              <img class="bi d-block mx-auto mb-1" width="24" height="24" src="{{ asset('images/student.svg') }}">
              Sinh viên
            </a>
          </li>
          @if(!auth()->user()->is_teacher)
          <li id="teacher-nav">
            <a href="{{route('teacher')}}" class="nav-link text-white">
              <img class="bi d-block mx-auto mb-1" width="24" height="24" src="{{ asset('images/teacher.svg') }}">
              Giáo viên
            </a>
          </li>
          <li id="admin-nav">
            <a href="{{route('user')}}" class="nav-link text-white">
              <img class="bi d-block mx-auto mb-1" width="24" height="24" src="{{ asset('images/admin1.svg') }}">
              Quản trị viên
            </a>
          </li>
          @endif
        </ul>
        <div>

        </div>
        <div  class="fw-normal text-info me-2">
          @if (Auth::check())
          <span>Xin chào, <b>{{ Auth::user()->name }}</b></span>
          @endif
        </div>
        <div>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-danger" type="submit">Đăng xuất</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</header>