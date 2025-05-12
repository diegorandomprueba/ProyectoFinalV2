<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'producte';
    protected $fillable = ['name', 'descr', 'price', 'stock', 'image', 'idCategoria'];

    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class, 'idCategoria');
    }

    public function comandes()
    {
        return $this->belongsToMany(Comanda::class, 'comanda_prod', 'idProducte', 'idComanda')
            ->withPivot('cant', 'has_to_comment');
    }

    /**
     * Obtiene las opiniones de este producto.
     */
    public function opiniones(): HasMany
    {
        return $this->hasMany(Opinion::class, 'product_id');
    }
    
    /**
     * Calcula la valoración media del producto.
     */
    public function averageRating()
    {
        return $this->opiniones()->avg('rating') ?: 0;
    }
    
    /**
     * Devuelve el número de valoraciones del producto.
     */
    public function ratingCount()
    {
        return $this->opiniones()->count();
    }

}