<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Actividadtipodato extends Model
{
    protected $table = 'actividadtipocontenido';
    protected $fillable = [
                            'empresauid', 
                            'contenidotipoid', 
                            'tipoactid',
                            'etiqueta',
                            'orden',
                            'idlista',
                            'mostar',
                            'status'
    ];
}
