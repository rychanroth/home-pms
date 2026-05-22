<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'name', 
    'product_type_id', 
    'base_unit', 
    'category_id', 
    'description', 
    'selling_price', 
    'stock_quantity', 
    'expiration_date', 
    'image', 
    'is_active'
])]
class Product extends Model
{
    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class);
    }
}
