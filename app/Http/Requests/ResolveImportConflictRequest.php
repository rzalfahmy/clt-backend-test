<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ResolveImportConflictRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('manage-clt-data');
    }

    public function rules(): array
    {
        return [
            'decision' => ['required', 'in:keep_existing,accept_incoming'],
        ];
    }
}
