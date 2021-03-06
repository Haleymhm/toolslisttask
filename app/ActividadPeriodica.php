<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoGenerateUuid;

class ActividadPeriodica extends Model
{
    use AutoGenerateUuid;
    protected $table = 'actividadperiodica';
    public $incrementing = false; /* Desactiva ID de la Tabla como autoincrement */
    protected $keyType = 'string'; /* Activa UUID de la Tabla como valor de 128bit */

    protected $fillable = [
                            'empresauid',
                            'uniopuid',
                            'tipoactuid',
                            'useruid',
                            'descrip',
                            'ubica',
                            'fechai',
                            'fechaf',
                            'periocidad',
                            'tipoperiodo',
                            'programauid',
                            'status'
    ];
}
