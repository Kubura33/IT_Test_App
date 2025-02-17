<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category_name' => $this->category->name,
            'department_name' => $this->department->name,
            'manufacturer' => $this->manufacturer->name,
            'product_number' => $this->product_number,
            'upc' => $this->upc,
            'sku' => $this->sku,
            'regular_price_sale' => $this->regular_price_sale,
            'sale_price' => $this->sale_price,
            'description' => $this->description,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
