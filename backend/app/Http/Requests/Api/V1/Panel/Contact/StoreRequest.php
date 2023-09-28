<?php

namespace App\Http\Requests\Api\V1\Panel\Contact;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id'  => 'required|int|exists:deals,id',
            'name'  => 'required|string|max:250',
            'phone' => 'required|string|max:50',
            'text'  => 'required|max:5000'
        ];
    }
}
