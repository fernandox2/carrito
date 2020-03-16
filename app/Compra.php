<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $fillable=[
        'compra', 'monto', 'empresa_id'
    ];

}
