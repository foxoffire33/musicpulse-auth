<?php

namespace App\Models;

use App\Traits\Blendable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use SoftDeletes, HasFactory, Blendable;

    protected $keyType = 'string';
    public $primaryKey = "id";
    public $incrementing = false;

    protected $fillable = ['id','device_name','device_token','device_os','os_version','app_version'];
    protected $hidden = ['created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'device_user');
    }

}