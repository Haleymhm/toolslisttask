<?php

namespace App;

use Caffeinated\Shinobi\Traits\ShinobiTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\AutoGenerateUuid;

class User extends Authenticatable
{
    use Notifiable, ShinobiTrait, HasApiTokens, AutoGenerateUuid, SoftDeletes;

    public $incrementing = false; /* Desactiva ID de la Tabla como autoincrement */

    protected $keyType = 'string'; /* Activa UUID de la Tabla como valor de 128bit */

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','name', 'email', 'cargo','photo','zonetime','language','password','uidempresa','status','vista','active', 'activation_token','misact','solomisact'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token', 'activation_token',
    ];

    public function getAvatarUrl()
        {

            return asset("upload/avatar".$this->photo);

        }


}
