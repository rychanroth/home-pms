<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ProductScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // We don't apply this globally. We will call this manually via a local scope in the Model.
    }

    /**
     * This is a custom local scope extension we'll attach to the Product model.
     * We pass the request filters directly into it.
     */
    public function applyFilters(Builder $query, array $filters): void
    {
        // 1. Search by name
        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        // 2. Filter by Product Type
        if (!empty($filters['product_type_id'])) {
            $query->where('product_type_id', $filters['product_type_id']);
        }

        // 3. Filter by Category
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // 4. Filter by Stock Status
        if (isset($filters['stock_status']) && $filters['stock_status'] !== '') {
            if ($filters['stock_status'] === 'low_stock') {
                $query->whereBetween('stock_quantity', [1, 5]);
            } elseif ($filters['stock_status'] === 'out_of_stock') {
                $query->where('stock_quantity', 0);
            } elseif ($filters['stock_status'] === 'in_stock') {
                $query->where('stock_quantity', '>', 5);
            }
        }

        // 5. Filter by Expiry Status
        if (isset($filters['expiry_status']) && $filters['expiry_status'] !== '') {
            if ($filters['expiry_status'] === 'expired') {
                $query->where('expiration_date', '<', now());
            } elseif ($filters['expiry_status'] === 'expiring_soon') {
                $query->whereBetween('expiration_date', [now(), now()->addDays(30)]);
            } elseif ($filters['expiry_status'] === 'no_expiry') {
                $query->whereNull('expiration_date');
            }
        }
    }
}