<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFireReportRequest extends FormRequest
{
    /**
     * Hanya pelapor yang boleh membuat laporan.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === 'reporter';
    }

    /**
     * Aturan validasi input pembuatan laporan.
     */
    public function rules(): array
    {
        return [
            'area_id' => ['required', 'exists:areas,id'],
            'title' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:5000'],
            'occurred_at' => ['required', 'date', 'before_or_equal:now'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'condition_level' => ['required', Rule::in(['low', 'medium', 'high'])],
            'evidence' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }
}
