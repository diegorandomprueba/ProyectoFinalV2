<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subcategoria;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categoria';
    protected $fillable = ['name', 'descr'];

    public function subcategorias()
    {
        return $this->hasMany(Subcategoria::class, 'idCategoria');
    }
}