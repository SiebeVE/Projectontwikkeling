<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PossibleAnswer extends Model
{
	use SoftDeletes;
	/**
	 * The attributes that are mass assignable
	 *
	 * @var array
	 */
	protected $fillable = [
		'answer',
		'default_question_id'
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

	/**
	 * Get the default question of this possible answer
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function defaultQuestions()
	{
		return $this->belongsTo('App\DefaultQuestion');
	}
}
