<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ImportSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('manage-clt-data');
    }

    public function rules(): array
    {
        return [
            'strategy' => ['required', 'in:overwrite,skip,reject,manual'],
            'payload' => ['nullable', 'string'],
            'json_file' => ['nullable', 'file', 'mimes:json,txt'],
        ];
    }

    public function after(): array
    {
        return [
            function ($validator) {
                if (! $this->filled('payload') && ! $this->hasFile('json_file')) {
                    $validator->errors()->add('payload', 'Provide a JSON payload or upload a JSON file.');
                }
            },
        ];
    }
}
