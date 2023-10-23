<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Carbon;

class UserController extends Controller
{
    public function index(Request $request){
        $records_per_page = 10;

        $keyword = $request->input('keyword');

        $users = User::searchAdmin($keyword, $records_per_page);
    
        $users->appends(['keyword' => $keyword]);
        return view('user.index',compact('users','keyword'));
    }

    public function store(UserRequest $request)
    {
        $data = $request->all();

        $request->validated();

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

    public function update(UserRequest $request){
        $data = $request->all();
        $request->validated();

        $record = User::findOrFail($data['userId']);
        $record->name =  $data['name'];
        $record->save();

        $message = "Chỉnh sửa thành công!";

        return redirect()->back()->withErrors($message);
    }
}
