<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('manage-clt-data');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:suppliers,name'],
        ];
    }
}
