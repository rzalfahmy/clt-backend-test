<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreLayupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('manage-clt-data');
    }

    public function rules(): array
    {
        $supplier = $this->route('supplier');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('layups', 'name')->where(fn ($query) => $query->where('supplier_id', $supplier->id)),
            ],
        ];
    }
}
