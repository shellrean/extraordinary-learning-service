<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionBankStore extends FormRequest
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
            'code'  => 'required',
            'mc_count' => 'required|int',
            'mc_option_count' => 'required|int',
            'esay_count'    => 'required|int',
            'percentage'    => 'required|array',
            'subject_id'    => 'required|exists:subjects,id'
        ];
    }
}
