<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class); 
    }

    public function productes()
    {
        return $this->belongsToMany(Producte::class, 'comanda_prod', 'idComanda', 'idProducte')
            ->withPivot('cant', 'has_to_comment');
    }
}