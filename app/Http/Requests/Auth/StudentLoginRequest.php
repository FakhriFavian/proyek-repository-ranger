<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class StudentLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'identitas' => ['required', 'string', 'max:30'],
            'password' => ['required', 'string'],
        ];
    }
}
