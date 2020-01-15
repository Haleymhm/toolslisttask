<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Actividadcontenido extends Model
{
    protected $table = 'actividadcontenido';
    protected $fillable = [
            'empresauid', 
            'uniopuid', 
            'tipoactuid',
            'actividaduid', 
            'contenidotipoactuid', 
            'valortexto', 
            'valornumero', 
            'valorfecha', 
            'valorcarpeta', 
            'valorlista',
            'idlista',
            'status'
         ];
}
