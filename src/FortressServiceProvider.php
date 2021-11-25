<?php

namespace Tmkzmu\Fortress;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class FortressServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->configurePublishing();
        $this->configureRoutes();
        $this->configureMigrations();
        $this->configureEmailUrls();
    }

    /**
     * Configure the publishable resources offered by the package.
     *
     * @return void
     */
    protected function configurePublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../stubs/fortress.php' => config_path('fortress.php'),
            ], 'fortress-configs');

            $this->publishes([
                __DIR__ . '/../stubs/actions/Register.php'          => app_path('Actions/Fortress/Register.php'),
                __DIR__ . '/../stubs/actions/Login.php'             => app_path('Actions/Fortress/Login.php'),
                __DIR__ . '/../stubs/actions/EmailVerification.php' => app_path('Actions/Fortress/EmailVerification.php'),
                __DIR__ . '/../stubs/actions/PasswordReset.php'     => app_path('Actions/Fortress/PasswordReset.php'),
                __DIR__ . '/../stubs/actions/UpdatePassword.php'    => app_path('Actions/Fortress/UpdatePassword.php'),
                __DIR__ . '/../stubs/actions/UpdateProfile.php'     => app_path('Actions/Fortress/UpdateProfile.php'),
            ], 'fortress-actions');

            $this->publishes([
                __DIR__ . '/../stubs/validations/ValidationRules.php' => app_path('Actions/Fortress/Validations/ValidationRules.php'),
            ], 'fortress-validations');
        }
    }

    /**
     * Configure package routes
     */
    public function configureRoutes()
    {
        Route::group([
            'prefix'     => config('fortress.routes.prefix'),
            'middleware' => config('fortress.routes.middleware')
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');
        });
    }

    public function configureMigrations()
    {
        if (Features::isFeatureEnabled(Features::EMAIL_VERIFICATION)) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }

    public function configureEmailUrls()
    {
        VerifyEmail::$createUrlCallback = function ($notifiable) {
            return sprintf(
                '%s%s?%s',
                Config::get('fortress.emails.callback_url'),
                Config::get('fortress.emails.email_verification_prefix'),
                http_build_query(
                    [
                        'expiration' => Carbon::now()->addMinutes(config('fortress.auth.verification_expire', 60))->timestamp,
                        'id' => $notifiable->getKey(),
                        'hash' => sha1($notifiable->getEmailForVerification()),
                        'token' => $notifiable->getToken(),
                    ]
                )
            );
        };

        ResetPassword::$createUrlCallback = function ($notifiable, $token) {
            return sprintf(
                '%s%s?%s',
                Config::get('fortress.emails.callback_url'),
                Config::get('fortress.emails.password_reset_prefix'),
                http_build_query(
                    [
                        'email' => $notifiable->getEmailForPasswordReset(),
                        'token' => $token,
                    ]
                )
            );
        };
    }
}
