<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoGenerateUuid;

class Documentos extends Model
{
    use AutoGenerateUuid;
    protected $table = 'documentos';
    public $incrementing = false; /* Desactiva ID de la Tabla como autoincrement */
    protected $keyType = 'string'; /* Activa UUID de la Tabla como valor de 128bit */

    protected $fillable = [
    	'empresauid',
    	'carpetauid',
    	'actividaduid',
        'contenidouid',
    	'nombre',
        'nombrefisico',
        'thumbnails',
    	'extension',
    	'publico',
    	'status'
  
    ];

    
}
