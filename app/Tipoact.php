<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Tipoact extends Model
{

    protected $table = 'tipoacts';
    protected $fillable = [
                            'empresauid',
                            'titulo',
                            'tipoactdescrip',
                            'tipoactcolor',
                            'status',
                            'parent',
                            'orden',
                            'tvista',
                            'mcal',
                            'mind',
                            'obligatorio',
                            'comporta',
                            'prefijo',
                            'uid'

    ];
}
