<?php

namespace App\Providers;

use App\Support\Charts\Lavacharts;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Lavacharts::class);

        AliasLoader::getInstance()->alias('Lava', \App\Support\Facades\Lava::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Mismo enlace que enviaba Laravel 5.3 en el correo de recuperar contraseña.
        // (El nombre "password.reset" lo usa la ruta POST de routes/web.php.)
        ResetPassword::createUrlUsing(function ($user, string $token) {
            return url('password/reset', $token);
        });
    }
}
