<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'phase_id',
		'sort',
		'question',
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
	 * Give all answers of question
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function answers()
	{
		return $this->hasMany('App\Answer');
	}

	/**
	 * Give phase of question
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function phases()
	{
		return $this->belongsTo('App\Phase');
	}
}
