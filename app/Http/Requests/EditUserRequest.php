<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditUserRequest extends FormRequest
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
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên không được bỏ trống',
            'name.string' => 'Nhập tên là chữ cái',
            'name.min' => 'Tên phải nhiều hơn 3 kí tự',
        ];
    }
}
