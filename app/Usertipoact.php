<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usertipoact extends Model
{
    protected $table = 'tipoact_user';
    protected $fillable = [
        'tipoacts_id', 
        'user_id'
    ];
}