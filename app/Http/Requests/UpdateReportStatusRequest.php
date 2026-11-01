<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReportStatusRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in([
                'under_review', 'needs_attention',
                'ready_to_forward', 'forwarded', 'closed',
            ])],
            'note' => ['nullable', 'string', 'max:2000'],
            'forwarded_to' => ['nullable', 'string', 'max:160'],
        ];
    }
}
