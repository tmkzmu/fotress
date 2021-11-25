<?php

namespace Tmkzmu\Fortress;

class Features
{
    // Features' names
    public const REGISTER = 'register';
    public const LOGIN = 'login';
    public const LOGOUT = 'logout';
    public const PASSWORD_RESET = 'password-reset';
    public const EMAIL_VERIFICATION = 'email-verification';
    public const UPDATE_PASSWORD = 'update-password';
    public const UPDATE_PROFILE = 'update-profile';

    public static function isFeatureEnabled(string $string): bool
    {
        return in_array($string, config('fortress.features', []));
    }

    public static function getUriFor(string $feature)
    {
        return config('fortress.routes.paths.' . $feature);
    }

    public static function getControllerClass()
    {
        return config('fortress.controller_class');
    }
}
