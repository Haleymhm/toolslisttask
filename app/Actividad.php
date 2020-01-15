<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use App\Traits\AutoGenerateUuid;


class Actividad extends Model
{
    //use AutoGenerateUuid;

    public $incrementing = false; /* Desactiva ID de la Tabla como autoincrement */

    protected $keyType = 'string'; /* Activa UUID de la Tabla como valor de 128bit */

    protected $fillable = [
                            'empresauid',
                            'useruid',
                            'unidadopuid',
                            'tipoactividaduid',
                            'actividadcodigo',
                            'actividadtitulo',
                            'actividaddescip',
                            'actividadinicio',
                            'actividadfin',
                            'actividadcolor',
                            'actividadlugar',
                            'actividadstatus',
                            'programauid',
                            'actperiocidauid',
                            'actividadgrupouid',
                            'actidadbusq',
                            'created_at',
                            'updated_at'
    ];
}
