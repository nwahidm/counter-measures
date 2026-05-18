<?php

namespace App\Providers;

use App\Models\Content;
use App\Models\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\LengthAwarePaginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrapFive();

       

        if (!$this->app->isLocal()) {
        // if (!$this->app->isLocal()) {
            $this->app['request']->server->set('HTTPS', true);
        }

        
    }

  
}
