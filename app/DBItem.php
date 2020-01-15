<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoGenerateUuid;

class DBItem extends Model
{
    use AutoGenerateUuid;
    protected $table = 'dbitem';
    public $incrementing = false; /* Desactiva ID de la Tabla como autoincrement */

    protected $keyType = 'string'; /* Activa UUID de la Tabla como valor de 128bit */

    protected $fillable = [
                            'empresauid',
                            'uniopuid',
                            'dashboarduid',
                            'itemtipo',
                            'tipoactuid',
                            'agrupartipocontuid',
                            'itemoperacion',
                            'itempgrafico',
                            'itemdesde',
                            'itempos',
                            'status',
                            'id'
    ];
}
