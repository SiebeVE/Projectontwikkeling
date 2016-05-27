<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    protected $fillable = [
        'name'
    ];

    /**
     * Get all the projects associated with the given tag
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function projects() {
        $this->belongsToMany('App\Project')->withTimestamps();
    }
}
