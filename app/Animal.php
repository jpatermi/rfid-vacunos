<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Animal extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'animal_rfid',
        'photo',
        'gender',
        'birthdate',
        'breed_id',
        'mother_rfid',
        'father_rfid',
        'age_group_id',
        'farm_id',
        'area_id',
        'lct1_id',
        'lct2_id',
        'lct3_id',
        'user_id',
        'status_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

     /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
/*        'created_at',
        'updated_at',*/
        'deleted_at',
    ];
    //protected $dateFormat = 'd-m-Y H:i:s';

    /**
     * Get the comments for the breed.
    */
    public function breed()
    {
        return $this->belongsTo('App\Breed');
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
    /**
     * Get the comments for the lct3.
    */
    public function lct3()
    {
        return $this->belongsTo('App\Lct3');
    }
    /**
     * Get the comments for the user.
    */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    /**
     * Get the comments for the vaccinations.
    */
    public function vaccinations()
    {
        return $this->belongsToMany('App\Vaccination')->using('App\AnimalVaccination')->withPivot('application_date', 'id')->wherePivot('deleted_at', null);
    }
    /**
     * Get the comments for the age group.
    */
    public function ageGroup()
    {
        return $this->belongsTo('App\AgeGroup');
    }
    /**
     * Get the comments for the animals.
    */
    public function animalLocations()
    {
        return $this->hasMany('App\AnimalLocation');
    }
}
