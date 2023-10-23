<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LessonRequest extends FormRequest
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
            'name' => 'required',
            'start' => 'required',
            'end' => 'required',
            'date' => 'required',
        ];
    }

    public function messages(){
        return [
            'name.required' => 'Tên không được bỏ trống',
            'start.required' => 'Thời gian bắt đầu không được bỏ trống',
            'end.required' => 'Thời gian kết thúc không được bỏ trống',
            'date.required' => 'Ngày học không được bỏ trống',
        ];
    }
}
