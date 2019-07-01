<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnimalLocation extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'animal_id',
    	'farm_id',
        'area_id',
        'lct1_id',
        'lct2_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at',
        //'created_at',
        'updated_at',
    ];

     /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
    	'created_at',
    ];
    /**
     * Get the comments for the farm.
    */
    public function animal()
    {
        return $this->belongsTo('App\Animal');
    }
    /**
     * Get the comments for the farm.
    */
    public function farm()
    {
        return $this->belongsTo('App\Farm');
    }
    /**
     * Get the comments for the area.
    */
    public function area()
    {
        return $this->belongsTo('App\Area');
    }
    /**
     * Get the comments for the lct1.
    */
    public function lct1()
    {
        return $this->belongsTo('App\Lct1');
    }
    /**
     * Get the comments for the lct2.
    */
    public function lct2()
    {
        return $this->belongsTo('App\Lct2');
    }
}