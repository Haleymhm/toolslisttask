<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Grupotipoact extends Model
{

    protected $table = 'groupstipact';
    protected $fillable = [
                        'empresauid',
                        'titulo',
                        'parent',
                        'orden',
                        'icono',
                        'status' 
        
];
}
