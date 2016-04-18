<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [

        'user_id',
        'name',
        'description',

    ];

    protected $dates = ['deleted_at'];

    public function users() {

        return $this->belongsTo('App\User');
    }

    public function phases() {

        return $this->hasMany('App\Phase');
    }
}
