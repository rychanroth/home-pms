<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'parent_id', 'product_type_id', 'image', 'is_active'])]
class Category extends Model
{
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    /**
     * Checks if a given category ID is an ancestor (parent, grandparent, etc.) of this category.
     * Used to prevent infinite circular references.
     */
    public function isDescendantOf($targetId): bool
    {
        // Start with the current category's parent
        $ancestor = $this->parent;

        // Keep looking up the tree until we hit the top (null)
        while (!is_null($ancestor)) {
            // Did we find the target ID in our family tree?
            if ($ancestor->id == $targetId) {
                return true; // YES! This would cause a loop.
            }

            // Move up one level: great-grandparent -> great-great-grandparent, etc.
            $ancestor = $ancestor->parent;
        }

        // We reached the top of the tree and never found the target. Safe!
        return false;
    }
}
