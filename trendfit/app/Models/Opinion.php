<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Producto;


class Opinion extends Model
{
    use HasFactory;
    

    protected $table = 'opiniones';
    public $timestamps = false;
    protected $fillable = ['product_id', 'user_id', 'user_name', 'rating', 'comment', 'date'];
    
    protected $casts = [
        'date' => 'datetime',
        'rating' => 'integer',
    ];
    
    /**
     * Obtiene el producto asociado a esta opinión.
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'product_id');
    }
    
    /**
     * Obtiene el usuario que hizo esta opinión.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}