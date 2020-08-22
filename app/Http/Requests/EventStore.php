<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventStore extends FormRequest
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
            'location'      => 'required',
            'title' => 'required',
            'body'  => 'required',
            'date'  => 'required',
            'time'  => 'required',
            'settings' => 'array'
        ];
    }
}
