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
		'password',
		'remember_token',
		"token",
		"verified",
		"tempMail",
	];

	/**
	 * Function that is standard execute on events
	 */
	public static function boot()
	{
		parent::boot();

		//Executed when a new user is made
		static::creating(function ($user)
		{
			$user->token = str_random(30);
		});
	}

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
		return $this->firstname . " " . $this->lastname;
	}

	/**
	 * Handle database when the email is confirmed
	 */
	public function confirmEmail()
	{
		$this->verified = true;
		$this->token = NULL;

		$this->save();
	}

	/**
	 * Handle database when the changed email is confirmed
	 */
	public function confirmChangedEmail()
	{
		$this->token = NULL;
		$this->email = $this->tempMail;
		$this->tempMail = NULL;

		$this->save();
	}

	/**
	 * Check if the user is an admin
	 *
	 * @return bool
	 */
	public function isAdmin()
	{
		return ($this->is_admin == "1" ? true : false);
	}

	/**
	 * Make the current user an admin
	 */
	public function makeAdmin()
	{
		$this->is_admin = "1";
		$this->save();
	}

	/**
	 * Remove the current user as an admin
	 */
	public function removeAdmin()
	{
		$this->is_admin = "0";
		$this->save();
	}

	/**
	 * Function to toggle the admin boolean
	 */
	public function toggleAdmin()
	{
		if ($this->isAdmin())
		{
			$this->removeAdmin();
		}
		else
		{
			$this->makeAdmin();
		}
	}
}
