<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Unidadop extends Model
{

	protected $table = 'unidadops';
    protected $fillable = [
                            'empresauid', 
                            'unidadopuid', 
                            'unidadopnombre',
                            'unidadopstatus', 
                            'deleted'
    ];


}
