<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PossibleAnswer extends Model
{
	/**
	 * The attributes that are mass assignable
	 *
	 * @var array
	 */
	protected $fillable = [
		'answer',
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
	 * Get the question of this possible answer
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function questions()
	{
		return $this->belongsTo('App\Question');
	}
}
