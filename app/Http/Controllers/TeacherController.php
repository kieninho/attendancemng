<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeacherRequest;
use App\Http\Requests\TeacherUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use App\Exports\TeacherExport;
use Maatwebsite\Excel\Facades\Excel;




class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $records_per_page = 10;

        $keyword = $request->input('keyword');

        $teachers = User::searchTeacher($keyword, $records_per_page);

        $teachers->appends(['keyword' => $keyword]);
        return view('teacher.index', compact('teachers', 'keyword'));
    }

    public function store(TeacherRequest $request): RedirectResponse
    {
        $data = $request->all();

        $request->validated();

        $data['status'] = 1;
        $data['is_teacher'] = 1;
        $data['password'] = '123456';
        $data['password'] = Hash::make($data['password']);
        if (!empty($data['birthday'])) {
            $data['birthday'] = Carbon::createFromFormat('Y-m-d', $data['birthday'])->toDateTime();
        }
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
            $user->save();

            $message = "Xóa thành công!";
        }

        return redirect()->back()->withErrors($message);
    }

    public function update(TeacherUpdateRequest $request)
    {

        $data = $request->all();
        $record = User::findOrFail($data['teacherId']);

        if (isset($record)) {
            $request->validated();


            $record->fill([
                'name' => $data['name'],
                'phone' => $data['phone'],
            ]);

            if (!empty($data['birthday'])) {
                $data['birthday'] = Carbon::createFromFormat('Y-m-d', $data['birthday'])->toDateTime();
            }
            $record->birthday = $data['birthday'];

            $record->save();
            $message = "Cập nhật thành công";
        }


        return redirect()->back()->withErrors($message);
    }

    public function get($id)
    {
        $data = User::findOrFail($id);

        return response()->json($data);
    }

    public function export()
    {

        $teachers = User::getTeachersToExport();

        return Excel::download(new TeacherExport($teachers), "DS_Giao_Vien.xlsx");
    }

    public function deleteMulti(Request $request)
    {
        $teacherIds = $request->input('item_ids');
        $countStd = count($teacherIds);

        if ($countStd <= 0) {
            $message = "Thao tác không thành công !!!";

            return redirect()->back()->withErrors($message);
        }

        foreach ($teacherIds as $teacherId) {
            User::deleteById($teacherId);
        }

        $message = "Xóa thành công $countStd giáo viên !!!";
        return redirect()->back()->withErrors($message);
    }
}
