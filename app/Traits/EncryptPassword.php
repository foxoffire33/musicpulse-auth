<?php

namespace App\Traits;
use Illuminate\Support\Facades\Hash;


trait EncryptPassword
{
    /**
     * Hash password when changed
     */
    public static function bootEncryptPassword(){
        static::saving(function ($table) {
            if($table->isDirty('password')){
                $table->password = Hash::make($table->password);
            }
        });
    }
}