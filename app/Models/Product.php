<?php

namespace App\Models;

use App\Scopes\ProductScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Casts;

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
    protected function casts(): array
    {
        return [
            'expiration_date' => 'date',
            'selling_price' => 'decimal:2',
        ];
    }

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

    /**
     * A bridge to scope class.
     */
    public function scopeFilter($query, array $filters)
    {
        (new ProductScope)->applyFilters($query, $filters);
        return $query;
    }
}
