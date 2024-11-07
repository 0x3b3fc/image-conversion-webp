<?php

namespace phpsamurai\ImageConversion;

use Illuminate\Support\ServiceProvider;

class ImageConversionServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            // Register the command
            $this->commands([
                Commands\ConvertImagesToWebP::class,
            ]);
        }
    }
}
