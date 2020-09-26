<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamScheduleStore extends FormRequest
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
            'question_bank_id'  => 'required|exists:question_banks,id',
            'name'  => 'required',
            'date'  => 'required',
            'start_time' => 'required',
            'duration' => 'required|int',
            'type'  => 'required|int'
        ];
    }
}
