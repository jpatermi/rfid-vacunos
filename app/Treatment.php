<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Treatment extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];
    /**
     * Get the comments for the diseases.
    */
    public function diseases()
    {
        return $this->belongsToMany('App\Disease')
                    ->using('App\DiseaseTreatment')
                    ->withPivot('id', 'indication')
                    ->wherePivot('deleted_at', null);
    }
}
