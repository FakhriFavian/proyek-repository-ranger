<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StudentLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nis' => ['required', 'string'],
            'password' => ['required', 'string'],
            'item_id' => ['nullable', 'exists:items,id'],
            'tanggal' => ['nullable', 'string'],
            'jam' => ['nullable', 'regex:/^\d{2}\.\d{2} - \d{2}\.\d{2}$/'],
            'jumlah' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
