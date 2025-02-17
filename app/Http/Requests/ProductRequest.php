<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_number' => ['required', 'string', 'max:255', 'unique:products,product_number,' . $this->route('product')->id],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'manufacturer_id' => ['required', 'integer', 'exists:manufacturers,id'],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
            'upc' => ['required', 'string', 'max:255', 'unique:products,upc,' . $this->route('product')->id],
            'sku' => ['required', 'string', 'max:255', 'unique:products,sku,' . $this->route('product')->id],
            'regular_price_sale' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ];
    }
}
