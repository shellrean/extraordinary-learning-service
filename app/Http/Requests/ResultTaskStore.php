<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResultTaskStore extends FormRequest
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
            'student_task_id'   => 'required|exists:student_tasks,id',
            'point'             => 'required|int'
        ];
    }
}
