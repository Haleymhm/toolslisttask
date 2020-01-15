<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoGenerateUuid;

class Elemento extends Model
{
    use AutoGenerateUuid;
    protected $table = 'elementos';
    public $incrementing = false; /* Desactiva ID de la Tabla como autoincrement */
    protected $keyType = 'string'; /* Activa UUID de la Tabla como valor de 128bit */

    protected $fillable = [
                            'empresauid',
                            'listadouid',
                            'elemnombre',
                            'elemdescip',
                            'elempos',
                            'status'
    ];
}
