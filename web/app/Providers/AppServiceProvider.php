<?php

namespace App\Providers;

use Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		view()->composer('*', function ($view)
		{
			$view->userGuest = Auth::guest();
			if ($view->userGuest)
			{
				$view->authenticatedUser = NULL;
				$view->userIsAdmin = false;
				$view->userName = NULL;
			}
			else
			{
				$view->authenticatedUser = Auth::user();
				$view->userIsAdmin = Auth::user()->isAdmin();
				$view->userName = Auth::user()->getName();
			}
		});
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}
}
