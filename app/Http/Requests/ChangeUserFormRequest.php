<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeUserFormRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name' => 'string|unique:users',
            'email' => 'email|unique:users',
            'password' => 'string|min:6'
        ];
    }
}
