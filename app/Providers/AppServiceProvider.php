<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Forçar HTTPS em produção
        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }

        Passport::ignoreRoutes();
        
        // Configurar tempo de expiração dos tokens
        Passport::tokensExpireIn(now()->addMinutes(config('passport.token_expiration.access_token', 15)));
        Passport::refreshTokensExpireIn(now()->addMinutes(config('passport.token_expiration.refresh_token', 43200)));

        Password::defaults(function () {
            return $this->app->isProduction()
                ? Password::min(12)->mixedCase()->numbers()->symbols()->uncompromised()
                : Password::min(8);
        });
    }
}
