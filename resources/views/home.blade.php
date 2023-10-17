@extends('layouts.app')

@section('header')
@include('elements.header')
@endsection

@section('content')
<div class="container-fluid row mt-5">
<div class="col-md-2">
        <div class="list-group scrollbar overflow-auto" style="max-height: 400px;">
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
                        <p class="card-text text-center"><b class="text-info">Sinh Viên</b></p>
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
                        <p class="card-text text-center"><b class="text-info">Giáo Viên</b></p>
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
                        <p class="card-text text-center"><b class="text-info">20 Lớp học</b></p>
                    </div>
                </div>
                </a>
            </div>
        </div>
    </div>

</div>


<!-- <script>
    function selectAll() {
        var checkboxes = document.getElementsByName("item_ids[]");
        var selectAllCheckbox = document.getElementById("select-all");

        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = selectAllCheckbox.checked;
        }
    }

    function setCheckedSelectAll() {
        var checkboxes = document.getElementsByName("item_ids[]");
        var selectAllCheckbox = document.getElementById("select-all");

        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked == false) {
                selectAllCheckbox.checked = false;
                return;
            }
            selectAllCheckbox.checked = true;
        }
    }

    $(document).ready(function() {
        $('.edit-button').click(function() {
            var classId = $(this).data('id'); // Lấy giá trị ID từ thuộc tính data-id của nút được click
            $('#classId').val(classId); // Gán giá trị ID vào hidden input
            console.log(classId);
            
            $.ajax({
                url: '{{ route("get.class") }}/' + classId,
                type: 'get',
                success: function(response) {
                    $('#edit-class-name').val(response.name);
                    $('#edit-class-description').val(response.description);
                }
            });
        });
    });
</script> -->
@endsection

@section('footer')
@include('elements.footer')
@endsection