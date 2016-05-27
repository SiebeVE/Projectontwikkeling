<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Word extends Model
{
	use SoftDeletes;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['word'];

	/**
	 * The attributes that are mutated to dates
	 *
	 * @var array
	 */
	protected $dates = ['deleted_at'];
}
