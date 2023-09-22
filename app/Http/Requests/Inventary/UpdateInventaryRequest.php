<?php

namespace App\Http\Requests\inventary;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInventaryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required',
            'color_id' => 'required',
            'size_id' => 'required',
            'discount_id' => 'required',
            'category_id' => 'required',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ];
    }
}
