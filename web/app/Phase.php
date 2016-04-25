<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phase extends Model
{
    protected $fillable = [
        'name',
        'description',
        'start',
        'end',

    ];

    protected $dates = ['deleted_at'];

    public function projects() {

        return $this->belongsTo('App\Project');
    }

    public function questions() {

        return $this->hasMany('App\Question');
    }
}
