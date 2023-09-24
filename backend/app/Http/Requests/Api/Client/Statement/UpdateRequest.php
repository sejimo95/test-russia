<?php

namespace App\Http\Requests\Api\Client\Statement;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('statement'),
            'user_id' => auth()->guard('client')->user()->id
        ]);
    }

    public function rules()
    {
        return [
            'id' => 'required|exists:statement,id',
            'user_id' => 'required|numeric|exists:users,id',
            'name' => 'required|string|max:250|min:3',
            'date' => 'required|date'
        ];
    }
}
