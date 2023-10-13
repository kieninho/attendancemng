<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Carbon;

class UserController extends Controller
{
    public function index(Request $request){
        $records_per_page = 10;
        $current_page = $request->query('page', 1);

        $keyword = $request->input('keyword');
        $users = User::search($keyword)->where('is_teacher',0)
                ->orderBy('created_at','desc')->paginate($records_per_page);

        return view('user.index',compact('users','keyword'));
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $request->validate(
            [
                'name' => 'required|string|min:3|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|min:6|',
            ],
            [
                'name.required' => 'Tên không được bỏ trống',
                'name.string' => 'Nhập tên là chữ cái',
                'name.min' => 'Tên phải nhiều hơn 3 kí tự',
                'email.required' => 'Email không được bỏ trống',
                'email.email' => 'Email không hợp lệ',
                'email.string' => 'Email không hợp lệ',
                'email.unique' => 'Email này đã được đăng ký',
                'password.required' => 'Mật khẩu không được bỏ trống',
                'password.min' => 'Độ dài mật khẩu lớn hơn 6 ký tự',

            ],
        );

        if($data['password'] != $data['password2']){
            $message = 'Mật khẩu không khớp';
            return redirect()->back()->withErrors($message);
        }

        $data['status'] = 1;
        $data['is_teacher'] = false;
        $data['password'] = Hash::make($data['password']);
        
        $result = User::create($data);

        if ($result) {
            $message = 'Thêm mới thành công!';
        } else {
            $message = 'Thêm mới không thành công!';
        }
        return redirect()->back()->withErrors($message);
    }

    public function get($id)
    {
        $data = User::findOrFail($id);

        return response()->json($data);
    }

    public function update(Request $request){
        $data = $request->all();
        $request->validate([
            'name' => 'required|string|min:3|max:255',
        ],[
            'name.required' => 'Tên không được bỏ trống',
            'name.string' => 'Nhập tên là chữ cái',
            'name.min' => 'Tên phải nhiều hơn 3 kí tự',
        ]);

        $record = User::findOrFail($data['userId']);
        $record->name =  $data['name'];
        $record->save();

        $message = "Chỉnh sửa thành công!";

        return redirect()->back()->withErrors($message);
    }
}
