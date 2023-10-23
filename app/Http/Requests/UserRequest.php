<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:6|',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên không được bỏ trống',
            'name.string' => 'Nhập tên là chữ cái',
            'name.min' => 'Tên phải nhiều hơn 3 kí tự',
            'email.required' => 'Email không được bỏ trống',
            'email.email' => 'Email không hợp lệ',
            'email.string' => 'Email không hợp lệ',
            'email.unique' => 'Email này đã được đăng ký',
            'password.required' => 'Mật khẩu không được bỏ trống',
            'password.min' => 'Độ dài mật khẩu lớn hơn 6 ký tự',
        ];
    }
}
