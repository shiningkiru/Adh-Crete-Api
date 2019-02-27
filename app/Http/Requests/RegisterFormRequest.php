<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterFormRequest extends FormRequest
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
            'id' => 'nullable|exists:users,id',
            'firstName' => 'required|string|max:190',
            'lastName' => 'nullable|string|max:190',
            'email' => 'required|email|unique:users,id',
            'mobileNumber' => 'required|string',
            'isAdmin' => 'required|in:true,false',
            'isActive' => 'required|in:true,false',
            'profilePic' => 'nullable|file',
        ];
    }
}
