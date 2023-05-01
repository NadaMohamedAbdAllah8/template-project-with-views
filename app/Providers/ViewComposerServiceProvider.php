<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //admin\layouts\header.blade.php
        view()->composer('admin.layouts.header', function ($view) {
            $view->with([
                'admin_name' => Auth::guard('admin')->user()->name,
            ]);
        });
    }
}
