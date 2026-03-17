<?php

namespace App\Models;
use App\Models\Categoria;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}
