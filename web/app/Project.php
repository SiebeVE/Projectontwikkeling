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

    public function users() {

        return $this->belongsTo('App\User');
    }

    public function phases() {

        return $this->hasMany('App\Phase');
    }
}
