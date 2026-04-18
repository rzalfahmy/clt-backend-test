<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreLayerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('manage-clt-data');
    }

    public function rules(): array
    {
        $layup = $this->route('layup');

        return [
            'layer_order' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('layers', 'layer_order')->where(fn ($query) => $query->where('layup_id', $layup->id)),
            ],
            'thickness' => ['required', 'numeric', 'gt:0'],
            'width' => ['required', 'numeric', 'gt:0'],
            'angle' => ['required', 'numeric', 'between:-360,360'],
        ];
    }
}
