<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable=[
        'boleta', 'usuario_id', 'monto', 'empresa_id'
    ];
}
