<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiseaseTreatment extends Pivot
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'disease_id',
    	'treatment_id',
        'indication',
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
    protected $dates = ['deleted_at',];
    //protected $table = ['disease_treatmen',];

    /**
     * Get the comments for the disease.
    */
    public function disease()
    {
        return $this->belongsTo('App\Disease');
    }
    /**
     * Get the comments for the treatment.
    */
    public function treatment()
    {
        return $this->belongsTo('App\Treatment');
    }
}
