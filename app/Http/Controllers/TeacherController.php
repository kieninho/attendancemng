<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;



class TeacherController extends Controller
{
    public function index()
    {
        $teachers = User::where('is_teacher', 1)->where('status', 1)->get();
        return view('teacher.index', compact('teachers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->all();

        $request->validate(
            [
                'name' => 'required|string|min:3|max:255',
                'email' => 'required|string|email|max:255|unique:users',
            ],
            [
                'name.required' => 'Tên không được bỏ trống',
                'name.string' => 'Nhập tên là chữ cái',
                'name.min' => 'Tên phải nhiều hơn 3 kí tự',
                'email.required' => 'Email không được bỏ trống',
                'email.email' => 'Email không hợp lệ',
                'email.string' => 'Email không hợp lệ',
                'email.unique' => 'Email này đã được đăng ký',

            ],
        );

        $data['status'] = 1;
        $data['is_teacher'] = 1;
        $data['password'] = '123456';
        $data['password'] = Hash::make($data['password']);
        $data['birthday'] = Carbon::createFromFormat('d/m/Y', $data['birthday'])->toDateTime();

        $result = User::create($data);

        if ($result) {
            $message = 'Thêm mới thành công!';
        } else {
            $message = 'Thêm mới không thành công!';
        }
        return redirect()->back()->withErrors($message);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        if ($user) {
            $user->status = 0;
        }
        $user->save();
        return redirect()->back();
    }

    public function update(Request $request)
    {

        $data = $request->all();
        $record = User::findOrFail($data['teacherId']);

        if (isset($record)) {
            $request->validate([
                'name' => 'required|string|min:3|max:100',
            ], [
                'name.required' => 'Tên không được bỏ trống',
                'name.string' => 'Nhập tên là chữ cái',
                'name.min' => 'Tên phải nhiều hơn 3 kí tự',
            ]);


            $record->fill([
                'name' => $data['name'],
                'birthday' => Carbon::createFromFormat('d/m/Y', $data['birthday'])->toDateTime(),
                'phone' => $data['phone'],
            ]);

            $record->save();
        }


        return redirect()->back();
    }

    public function get($id)
    {
        $data = User::findOrFail($id);

        return response()->json($data);
    }
}
