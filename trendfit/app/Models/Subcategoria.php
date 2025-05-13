<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categoria;
use App\Models\Producto;


class Subcategoria extends Model
{
    use HasFactory;

    protected $table = 'subcategoria';
    protected $fillable = ['name', 'descr', 'idCategoria'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'idCategoria');
    }

    public function productes()
    {
        return $this->hasMany(Producto::class, 'idCategoria');
    }
}