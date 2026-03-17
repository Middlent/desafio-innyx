<?php

namespace App\Models;
use App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
