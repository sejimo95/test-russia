<?php

namespace App\Http\Requests\Api\Client\Statement;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation() {
        $this->merge(['user_id' => auth()->guard('client')->user()->id]);
    }

    public function rules()
    {
        return [
            'user_id' => 'required|numeric|exists:users,id',
            'name' => 'required|string|max:250|min:3',
            'date' => 'required|date'
        ];
    }
}
