<?php

namespace App\Models;

use App\Traits\Blendable;
use App\Traits\EncryptPassword;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory, EncryptPassword, Blendable, SoftDeletes;

    const USER_ROLE_ADMIN   = 0;
    const USER_ROLE_PARTNER = 1;
    const USER_ROLE_DEVICE  = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email', 'password','role'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'deleted_at','created_by','updated_by','deleted_by'];

    /**
     * All visable attribute when toArray is called.
     *
     * @var string[]
     */
    protected $visible = ['id','email','role','created_at','updated_at'];

    public $incrementing = false;
    protected $keyType = 'string';

    //Relations
    public function devices()
    {
       return $this->belongsToMany(Device::class,'device_user');
    }
}
