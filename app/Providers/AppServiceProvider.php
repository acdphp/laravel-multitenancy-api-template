<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Passport::ignoreRoutes();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! $this->app->isProduction()) {
            Model::preventLazyLoading();
            Model::preventAccessingMissingAttributes();
        }

        $this->forceScheme();
    }

    private function forceScheme(): void
    {
        $currentScheme = parse_url(config('app.url'))['scheme'] ?? 'https';
        URL::forceScheme($currentScheme);
    }
}
