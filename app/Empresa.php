<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoGenerateUuid;

class Empresa extends Model
{
    use AutoGenerateUuid;
    protected $table = 'empresas';
    public $incrementing = false; /* Desactiva ID de la Tabla como autoincrement */
    
    protected $keyType = 'string'; /* Activa UUID de la Tabla como valor de 128bit */

    protected $fillable = [
                            'empresanombre', 
                            'empresadireccion', 
                            'empresatelefono',
                            'empresaemail', 
                            'empresalogo',
                            'uiduser',
                            'rutrif',
                            'id'
    ];
    public function getLogoUrl()
        {
            
            return asset("upload/avatar/".$this->empresalogo);

        }
}
