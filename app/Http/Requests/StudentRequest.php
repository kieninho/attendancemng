<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|string|email|max:150'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên sinh viên không được bỏ trống',
            'name.string' => 'Nhập sinh viên lớp là chữ cái',
            'name.min' => 'Tên sinh viên phải nhiều hơn 3 kí tự',
            'email.required' => 'Email không được bỏ trống',
            'email.email' => 'Email sai định dạng',
        ];
    }
}
