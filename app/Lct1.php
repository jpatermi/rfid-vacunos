<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Lct1 extends Model
{
	use SoftDeletes;    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'area_id',
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
    protected $dates = ['deleted_at'];
    /**
     * Get the comments for the animals.
    */
    public function animals()
    {
        return $this->hasMany('App\Animal');
    }
    /**
     * Get the comments for the area.
    */
    public function area()
    {
        return $this->belongsTo('App\Area');
    }
    /**
     * Get the comments for the animals.
    */
    public function lct2s()
    {
        return $this->hasMany('App\Lct2');
    }
}
