<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'photo_left_offset',
        'latitude',
        'longitude'
    ];

    protected $dates = ['deleted_at'];

	/**
     * Get the user of the project
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users() {

        return $this->belongsTo('App\User');
    }

	/**
     * Get all the phases of this project
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function phases() {

        return $this->hasMany('App\Phase');
    }

    //public function setPhotoLeftOffset($value)
    //{
    //    if($value == "auto")
    //    {
    //        $value = 0;
    //    }
	//
    //    return $value;
    //}
}
