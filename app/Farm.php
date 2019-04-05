<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Farm extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the comments for the animals.
    */
    public function animals()
    {
        return $this->hasMany('App\Animal');
    }
    /**
     * Get the comments for the areas.
    */
    public function areas()
    {
        return $this->hasMany('App\Area');
    }
}
