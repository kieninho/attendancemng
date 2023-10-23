<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassRequest extends FormRequest
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
            'name' => 'required|string|min:2',
        ];
    }

    public function messages(){
        return [
            'name.required' => 'Tên lớp không được bỏ trống',
            'name.string' => 'Nhập tên lớp là chữ cái',
            'name.min' => 'Tên lớp phải nhiều hơn 2 kí tự',
        ];
    }
}
