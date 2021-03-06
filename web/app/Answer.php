<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
	use SoftDeletes;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'question_id',
		'answer',
		"multipleAnswers",
		"user_id"
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
