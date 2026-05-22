<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'description', 'requires_expiration', 'image', 'is_active'])]
class ProductType extends Model
{
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
