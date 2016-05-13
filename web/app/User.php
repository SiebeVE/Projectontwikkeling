<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'email',
		'password',
		'firstname',
		'lastname',
		'address',
		'postal_code',
		'city',
		'is_admin'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
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
	 * Give all answers of user
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function answers()
	{
		return $this->hasMany('App\Answer');
	}

	/**
	 * Give all projects of user
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function projects()
	{
		return $this->hasMany('App\Project');
	}

	/**
	 * Get the OAuth credentials
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function oauthcredential()
	{
		return $this->hasOne('App\OAuthCredential');
	}

	/**
	 * Give the full name of the user
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->firstname." ".$this->lastname;
	}
}
