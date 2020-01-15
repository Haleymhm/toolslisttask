<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permisorolh extends Model
{
    protected $table = 'permission_role';
    protected $fillable = [
        'role_id', 
        'permission_id'
];
}