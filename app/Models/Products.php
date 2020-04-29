<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "title",
        "product_number",
        "product_number_replacements",
        "product_number_inner",
        "product_model_id",
        "description",
        "price",
        "category_id",
        "product_recommend",
        "images"
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }

    public function productModel()
    {
        return $this->belongsTo(ProductModels::class);
    }
}
