<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Producto;
use App\Models\User;

class Comanda extends Model
{
    use HasFactory;

    protected $table = 'comanda';
    protected $fillable = [
        'idUsuari',
        'name', 
        'address', 
        'city', 
        'provincia', 
        'codigo_postal', 
        'date',
        'phone',
        'status',
        'payment_method',
        'total',
    ];

    public function user()
    {
        return $this->belongsTo(User::class); 
    }

    public function productes()
    {
        return $this->belongsToMany(Producto::class, 'comanda_prod', 'idComanda', 'idProducte')
            ->withPivot('cant', 'has_to_comment', 'size');
    }
}