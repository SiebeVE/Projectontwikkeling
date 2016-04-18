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

        $this->belongsTo('App\User');
    }

    public function phases() {

        $this->hasMany('App\Phase');
    }
}
