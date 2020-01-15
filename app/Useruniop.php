<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Useruniop extends Model
{
    public $incrementing = true;
    protected $table = 'uniop_user';
    protected $fillable = [
                            'user_id', 
                            'unidadopuid'
    ];

    
}
