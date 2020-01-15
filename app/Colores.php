<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Colores extends Model
{
    protected $table = 'colores';
    protected $fillable = [
                            'nombre',
                            'clase',
                            'color'
    ];
}
