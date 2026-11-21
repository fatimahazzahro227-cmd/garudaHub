<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:200'],
            'deskripsi' => ['required', 'string', 'max:5000'],
            'kategori' => ['required', 'string', 'max:50'],
            'budget_min' => ['required', 'integer', 'min:0', 'max:999999999999'],
            'budget_max' => ['required', 'integer', 'gte:budget_min', 'max:999999999999'],
            'batas_lelang' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'max' => ':attribute terlalu panjang/besar.',
            'integer' => ':attribute harus berupa angka bulat.',
            'budget_min.min' => 'Budget minimum tidak boleh negatif.',
            'budget_max.gte' => 'Budget maksimum harus >= budget minimum.',
            'batas_lelang.date_format' => 'Batas lelang harus tanggal yang valid.',
            'batas_lelang.after_or_equal' => 'Batas lelang tidak boleh tanggal yang sudah lewat.',
        ];
    }

    public function attributes(): array
    {
        return [
            'judul' => 'Judul',
            'deskripsi' => 'Deskripsi',
            'kategori' => 'Kategori',
            'budget_min' => 'Budget minimum',
            'budget_max' => 'Budget maksimum',
            'batas_lelang' => 'Batas lelang',
        ];
    }
}
