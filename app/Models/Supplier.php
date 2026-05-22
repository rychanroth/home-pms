<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'phone', 'address', 'is_active'])]
class Supplier extends Model
{
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
