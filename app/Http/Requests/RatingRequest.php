<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RatingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'skor' => ['required', 'integer', 'between:1,5'],
            'ulasan' => ['nullable', 'string', 'max:500'],
        ];
    }
}