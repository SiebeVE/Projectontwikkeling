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
		view()->composer('*', function ($view) {
			$view->authenticatedUser = Auth::user();
			$view->userIsAdmin = Auth::user()->isAdmin();
			$view->userName = Auth::user()->getName();
			$view->userGuest = Auth::guest();
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
