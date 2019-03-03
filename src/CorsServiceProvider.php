<?php
// edit from  https://github.com/spatie/laravel-cors
namespace Diengv\GdsCors;

use Illuminate\Support\ServiceProvider;
use Diengv\GdsCors\CorsProfile\CorsProfile;
use Diengv\GdsCors\CorsProfile\DefaultProfile;
use Diengv\GdsCors\Exceptions\InvalidCorsProfile;

class CorsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->isLaravel() && $this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/cors.php' => config_path('cors.php'),
            ], 'config');
        }

        $configuredCorsProfile = config('cors.cors_profile');

        if (! is_a($configuredCorsProfile, DefaultProfile::class, true)) {
            throw InvalidCorsProfile::profileDoesNotExtendDefaultProfile($configuredCorsProfile);
        }

        $this->app->bind(CorsProfile::class, $configuredCorsProfile);
    }

    public function register()
    {
        if ($this->isLaravel()) {
            $this->mergeConfigFrom(__DIR__.'/../config/cors.php', 'cors');
        }
    }

    protected function isLaravel()
    {
        return ! preg_match('/lumen/i', app()->version());
    }
}
