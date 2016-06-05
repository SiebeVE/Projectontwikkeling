<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DefaultQuestion extends Model
{
	use SoftDeletes;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		"sort",
		"question",
		"width"
	];

	/**
	 * The attributes that are mutated to dates
	 *
	 * @var array
	 */
	protected $dates = [
		'deleted_at',
	];

	/**
	 * Give all possible answers of question (multiplechoice)
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function possibleAnswers()
	{
		return $this->hasMany('App\PossibleAnswer');
	}

	/**
	 * Get all projects with this default question
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function projects()
	{
		return $this->belongsToMany('App\Project')->withTimestamps();
	}

}
