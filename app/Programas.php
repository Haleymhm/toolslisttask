<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoGenerateUuid;

class Programas extends Model
{
    use AutoGenerateUuid;
    protected $table = 'actividadprograma';
    public $incrementing = false; /* Desactiva ID de la Tabla como autoincrement */

    protected $keyType = 'string'; /* Activa UUID de la Tabla como valor de 128bit */

    protected $fillable = [
                            'empresauid',
                            'uniopuid',
                            'prognombre',
                            'progdescrip',
                            'progcolor',
                            'progicon',
                            'id'
    ];
}
