<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vaccination extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'characteristic',
        'expiration_date',
        'lot',
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
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'expiration_date'];
    /**
     * Get the comments for the animals.
    */
    public function AnimalVaccinations()
    {
        return $this->belongsToMany('App\Animal')
                    ->using('App\AnimalVaccination')
                    ->withPivot('application_date', 'id', 'dose')
                    ->wherePivot('deleted_at', null);
    }
}
