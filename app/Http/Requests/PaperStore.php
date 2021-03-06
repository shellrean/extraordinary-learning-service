<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaperStore extends FormRequest
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
            'classroom_subject_id'      => 'required|exists:classroom_subjects,id',
            'type'                      => 'required|in:lesson_plan,syllabus',
            'file'                      => 'mimes:pdf'
        ];
    }
}
