<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'farm_id'
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
     * Get the comments for the animals.
    */
    public function animals()
    {
        return $this->hasMany('App\Animal');
    }
    /**
     * Get the comments for the farm.
    */
    public function farm()
    {
        return $this->belongsTo('App\Farm');
    }
    /**
     * Get the comments for the animals.
    */
    public function lct1s()
    {
        return $this->hasMany('App\Lct1');
    }
    /*** Trae los Corrales de un Aprisco directamente ***/
    public function lct2s()
    {
        return $this->hasManyThrough('App\Lct2', 'App\Lct1');
    }
}
