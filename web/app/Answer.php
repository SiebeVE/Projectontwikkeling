<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'question_id',
		'answer',
		"multipleAnswers",
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
	 * Give the question of answer
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function questions()
	{
		return $this->belongsTo('App\Question');
	}

	/**
	 * Give user of answer
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function users()
	{
		return $this->belongsTo('App\User');
	}

	public function multipleAnswerdes()
	{
		return $this->hasMany('App\MultipleAnswerd');
	}
}
