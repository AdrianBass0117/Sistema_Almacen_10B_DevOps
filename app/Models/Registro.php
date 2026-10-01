<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registro extends Model
{
    protected $table = 'registros';

    protected $fillable = [
        'id_producto',
        'operacion',
        'nombre_producto',
        'cantidad_antes_operacion',
        'cantidad_despues_operacion',
        'precio_antes_operacion',
        'precio_despues_operacion',
        'created_at',
        'updated_at',
    ];

    public $timestamps = true;

    /**
     * Relación: cada registro pertenece a un producto
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
}
