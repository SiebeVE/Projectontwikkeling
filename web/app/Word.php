<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
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
