<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BidRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'harga' => ['required', 'integer', 'min:1', 'max:999999999999'],
            'durasi' => ['required', 'integer', 'min:1', 'max:3650'],
            'proposal' => ['required', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'integer' => ':attribute harus berupa angka bulat.',
            'harga.min' => 'Harga harus lebih dari 0.',
            'durasi.min' => 'Durasi minimal 1 hari.',
            'durasi.max' => 'Durasi maksimal 3650 hari.',
            'max' => ':attribute terlalu panjang/besar.',
        ];
    }

    public function attributes(): array
    {
        return ['harga' => 'Harga', 'durasi' => 'Durasi', 'proposal' => 'Proposal'];
    }
}
