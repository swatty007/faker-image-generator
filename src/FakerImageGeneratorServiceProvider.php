<?php

namespace Swatty007\FakerImageGenerator;

use Illuminate\Support\ServiceProvider;

class FakerImageGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('faker-image-generator.php'),
            ], 'config');

            // Publishing assets.
            $this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/faker-image-generator'),
            ], 'assets');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'faker-image-generator');
    }
}
