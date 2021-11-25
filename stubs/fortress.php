<?php

use Tmkzmu\Fortress\Controllers\FortressController;
use Tmkzmu\Fortress\Features;

return [

    /*
    |--------------------------------------------------------------------------
    | Features enables
    |--------------------------------------------------------------------------
    |
    | Enable or disable features
    |
    */

    'features' => [
        Features::REGISTER,
        Features::LOGIN,
        Features::EMAIL_VERIFICATION,
        Features::PASSWORD_RESET,
        Features::UPDATE_PASSWORD,
        Features::UPDATE_PROFILE,
    ],

    /*
    |--------------------------------------------------------------------------
    | Controller class
    |--------------------------------------------------------------------------
    |
    | Default controller class for handling requests
    |
    */

    'controller_class' => FortressController::class,

    /*
    |--------------------------------------------------------------------------
    | Routes configuration
    |--------------------------------------------------------------------------
    |
    | Paths and prefix for routes
    |
    */

    'routes' => [
        'prefix'                  => '',
        'middleware'              => ['api'],
        'auth_middleware'         => ['auth'],
        'login_throttle'          => '3,5',
        'reset_password_throttle' => '3,1',
        'paths'                   => [
            Features::REGISTER           => 'register',
            Features::LOGIN              => 'login',
            Features::LOGOUT             => 'logout',
            Features::EMAIL_VERIFICATION => 'email-verification',
            Features::PASSWORD_RESET     => 'password-reset',
            Features::UPDATE_PASSWORD    => 'update-password',
            Features::UPDATE_PROFILE     => 'update-profile',
        ],
    ],

    'auth' => [
        'verification_expire' => 60 // In minutes
    ],

    'emails' => [
        'callback_url'              => config('app.url'),
        'email_verification_prefix' => '/verify-email',
        'password_reset_prefix'     => '/reset-password',
    ],
];
