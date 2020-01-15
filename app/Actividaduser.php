<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Actividaduser extends Model
{

	protected $table = 'actividaduser';
    protected $fillable = [
                            'empresauid',
                            'actividaduid',
                            'useriud',
                            'nombre',
                            'email',
                            'responsable'
    ];


}
