<?php

namespace phpsamurai\ImageConversion;

use Illuminate\Support\ServiceProvider;

class ImageConversionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        // Publishing configuration file
        $this->publishes([
            __DIR__.'/../config/image-conversion.php' => config_path('image-conversion.php'),
        ], 'config');

        // Registering the command for console usage
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\ConvertImagesToWebP::class,
            ]);
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        // Register the ImageConversionService
        $this->app->singleton('image-conversion', function () {
            return new ImageConversionService();
        });
    }
}
