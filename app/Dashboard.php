<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoGenerateUuid;

class Dashboard extends Model
{
    use AutoGenerateUuid;
    protected $table = 'dashboard';
    public $incrementing = false; /* Desactiva ID de la Tabla como autoincrement */

    protected $keyType = 'string'; /* Activa UUID de la Tabla como valor de 128bit */

    protected $fillable = [
                            'id',
                            'empresauid',
                            'uniopuid',
                            'dbnom',
                            'dbdesc',
                            'dbpos',
                            'status',

    ];
}
