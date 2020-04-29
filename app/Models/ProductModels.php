<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductModels extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title'
    ];

    public function product()
    {
        return $this->hasMany(Products::class );
    }
}
