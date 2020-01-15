<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usertipoactmob extends Model
{
    protected $table = 'tipoact_usermob';
    protected $fillable = [
        'tipoacts_id',
        'user_id'
    ];
}
