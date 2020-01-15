<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Useractividad extends Model
{
    protected $table = 'actividaduser';
    protected $fillable = [
                            'empresauid',
                            'actividaduid',
                            'useruid',
                            'nombre',
                            'email',
                            'responsable'
                        ];
}
