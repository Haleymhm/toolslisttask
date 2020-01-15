<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoGenerateUuid;

class Listado extends Model
{
    use AutoGenerateUuid;
    protected $table = 'listados';
    public $incrementing = false; /* Desactiva ID de la Tabla como autoincrement */
    protected $keyType = 'string'; /* Activa UUID de la Tabla como valor de 128bit */

    protected $fillable = [
                            'empresauid',
                            'nombrelista',
                            'descplista',
                            'ver',
                            'status'
    ];
}
