<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'newpass' => 'required|min:6',
            'newpass2' => 'required|same:newpass',
        ];
    }

    public function messages()
    {
        return [
            'newpass.required' => 'Nhập mật khẩu mới',
            'newpass.min' => 'Độ dài mật khẩu lớn hơn 6 ký tự',
            'newpass2.required' => 'Nhập lại mật khẩu',
            'newpass2.same' => 'Không trùng khớp',
        ];
    }
}
