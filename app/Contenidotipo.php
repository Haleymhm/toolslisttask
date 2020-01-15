<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contenidotipo extends Model
{
    protected $table = 'contenidotipo';
    protected $fillable = [
                            'conttipodesc',
                            'tipodato',
                            'id'
    ];
}
