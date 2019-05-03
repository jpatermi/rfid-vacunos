<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Disease extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'animal_id',
    	'veterinarian_id',
        'diagnostic_id',
        'cause_id',
        'responsible_id',
        'review_date',
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
    protected $dates = [
    	'review_date',
    	'deleted_at',
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
    public function veterinarian()
    {
        return $this->belongsTo('App\Veterinarian');
    }
    /**
     * Get the comments for the area.
    */
    public function diagnostic()
    {
        return $this->belongsTo('App\Diagnostic');
    }
    /**
     * Get the comments for the lct1.
    */
    public function cause()
    {
        return $this->belongsTo('App\Cause');
    }
    /**
     * Get the comments for the lct2.
    */
    public function responsible()
    {
        return $this->belongsTo('App\Responsible');
    }
    /**
     * Get the comments for the treatments.
    */
    public function treatments()
    {
        return $this->belongsToMany('App\Treatment')
                    ->using('App\DiseaseTreatment')
                    ->withPivot('indication', 'id')
                    ->wherePivot('deleted_at', null);
    }
}
