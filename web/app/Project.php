<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
	use SoftDeletes;
	protected $fillable = [
		'name',
		'description',
		'address',
		'photo_left_offset',
		'latitude',
		'longitude'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'user_id',
		"publishTime",
		"deleted_at",
		"created_by"
	];

	protected $dates = ['deleted_at', 'publishTime'];

	/**
	 * Get the user of the project
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function users()
	{

		return $this->belongsTo('App\User');
	}

	/**
	 * Get all the phases of this project
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function phases()
	{

		return $this->hasMany('App\Phase');
	}

	/**
	 * Get all the tags of this project
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function tags()
	{
		return $this->belongsToMany('App\Tag')->withTimestamps();
	}


	/**
	 * Get the current phase in time
	 *
	 * @return Phase currentPhase
	 */
	public function getCurrentPhase()
	{
		$currentPhase = NULL;
		foreach ($this->phases as $phase)
		{
			// Get the phase of the current date
			//dd(Carbon::instance($phase->start));
			$C_phaseEnd = Carbon::instance($phase->end);
			$C_phaseStart = Carbon::instance($phase->start);
			if (($C_phaseEnd->isFuture() && $C_phaseStart->isPast()))
			{
				$currentPhase = $phase;
				//dd($currentPhase);
			}
		}

		return $currentPhase;
	}
	//public function setPhotoLeftOffset($value)
	//{
	//    if($value == "auto")
	//    {
	//        $value = 0;
	//    }
	//
	//    return $value;
	//}
}
