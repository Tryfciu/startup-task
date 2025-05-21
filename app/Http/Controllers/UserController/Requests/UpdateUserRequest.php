<?php

namespace App\Http\Controllers\UserController\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest  extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'phoneNumber' => 'required|max:255',
            'emails' => 'required|array|min:1', // TODO add email validator, add email uniqueness validator
        ];
    }
}