<?php

namespace App\Providers;

use App\Http\Controllers\InfusionSoftController;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('partials.tags', function($view) {
            $view->with('tags', InfusionSoftController::getTags());
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
