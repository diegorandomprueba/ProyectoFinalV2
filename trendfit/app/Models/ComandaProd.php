<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Producto;
use App\Models\Comanda;


class ComandaProd extends Model
{
    use HasFactory;

    protected $table = 'comanda_prod';
    protected $fillable = ['idComanda', 'idProducte', 'cant', 'has_to_comment'];

    public function comanda()
    {
        return $this->belongsTo(Comanda::class, 'idComanda');
    }

    public function producte()
    {
        return $this->belongsTo(Producto::class, 'idProducte');
    }
}