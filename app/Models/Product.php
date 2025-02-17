<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'department_id',
        'department_id',
        'name',
        'product_number',
        'upc',
        'sku',
        'regular_price_sale',
        'sale_price',
        'description'
    ];

    public function department() : BelongsTo{
        return $this->belongsTo(Department::class, "department_id");
    }
    public function category() : BelongsTo{
        return $this->belongsTo(Category::class, "category_id");
    }
    public function manufacturer() : BelongsTo{
        return $this->belongsTo(Manufacturer::class, "manufacturer_id");
    }
}
