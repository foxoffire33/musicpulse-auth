<?php
/**
 * als je een deleted_by veld hebt dan moet je ook softdelete inladen in het model
 */

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait Blendable
{

    public static function bootBlendable()
    {
            // create a event to happen on updating
            static::updating(function ($table) {
                $table->updated_by = $table->updated_by ?? Auth::id();
            });

            // create a event to happen on deleting
            static::deleting(function ($table) {
                if (property_exists($table, 'deleted_by')) {
                    $table->deleted_by = $table->deleted_by ?? Auth::id();
                }
            });

            // create a event to happen on saving
            static::saving(function ($table) {});

            static::creating(function ($table) {
                //set properties to there defaults when not set
                $table->created_by ?? Auth::id();
                $table->id = Str::uuid();
            });
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'createdBy', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'createdBy', 'id');
    }

    public function deletedBy()
    {
        if (property_exists($this, 'deleted_by')) {
            return $this->belongsTo(User::class, 'deletedBy', 'id');
        }
        return null;
    }
}
