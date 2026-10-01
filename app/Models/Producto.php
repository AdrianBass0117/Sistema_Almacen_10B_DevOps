<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'precio',
        'cantidad',
        'imagen',
    ];

    // Relación con registros
    public function registros()
    {
        return $this->hasMany(Registro::class, 'id_producto');
    }
}
