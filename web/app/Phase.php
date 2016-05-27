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

	/**
	 * Fields that are hidden when showing array of data
	 *
	 * @var array
	 */
	protected $hidden = [
		"project_id",
		"deleted_at",
		"created_by"
	];

	protected $dates = ['deleted_at', 'start', 'end'];

	/**
	 * Get the project of this phase
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function projects()
	{
		return $this->belongsTo('App\Project');
	}

	/**
	 * Get all the questions of this phase
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function questions()
	{
		return $this->hasMany('App\Question');
	}
}
