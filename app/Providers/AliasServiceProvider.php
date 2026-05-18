<?php

namespace App\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class AliasServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
         $loader = AliasLoader::getInstance();
         
         $loader->alias('Captcha', \Mews\Captcha\Facades\Captcha::class);
         $loader->alias('Image', \Intervention\Image\Facades\Image::class);
         $loader->alias('FileHelper', \App\Helpers\FileHelper::class);
         $loader->alias('ResponseApi', \App\Helpers\ResponseApiHelper::class);
         $loader->alias('Excel', \Maatwebsite\Excel\Facades\Excel::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
