<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable=[
        'rut', 'razon_social', 'rubro', 'representante_legal', 'correo', 'estado_licencia', 'imagen_corporativa', 'ambiente_certificacion', 'ruta_firma_electronica', 'clave_firma', 'ciudad'
    ];


}
