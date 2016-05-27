<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MultipleAnswerd extends Model
{
	use SoftDeletes;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'possible_answer_id',
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
	public function answer()
	{
		return $this->belongsTo('App\Answer');
	}
}
