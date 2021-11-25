<?php

use Illuminate\Support\Facades\Route;
use Tmkzmu\Fortress\Features;

if (Features::isFeatureEnabled(Features::REGISTER)) {
    Route::post(Features::getUriFor(Features::REGISTER), [Features::getControllerClass(), 'register'])->name('register');
}

if (Features::isFeatureEnabled(Features::LOGIN)) {
    Route::post(Features::getUriFor(Features::LOGIN), [Features::getControllerClass(), 'login'])->name('login')->middleware('throttle:' . config('fortress.routes.login_throttle'));
    Route::post(Features::getUriFor(Features::LOGOUT), [Features::getControllerClass(), 'logout'])->name('logout')->middleware(config('fortress.routes.auth_middleware'));
}

if (Features::isFeatureEnabled(Features::EMAIL_VERIFICATION)) {
    Route::post(Features::getUriFor(Features::EMAIL_VERIFICATION), [Features::getControllerClass(), 'askEmailVerificationEmail'])->name('verification.ask')->middleware(config('fortress.routes.auth_middleware'));
    Route::get(Features::getUriFor(Features::EMAIL_VERIFICATION), [Features::getControllerClass(), 'emailVerification'])->name('verification.verify')->middleware(config('fortress.routes.auth_middleware'));
}

if (Features::isFeatureEnabled(Features::PASSWORD_RESET)) {
    Route::post(Features::getUriFor(Features::PASSWORD_RESET) . '/ask', [Features::getControllerClass(), 'askResetPassword'])->name('reset-password.ask')->middleware('throttle:' . config('fortress.routes.login_throttle'));
    Route::post(Features::getUriFor(Features::PASSWORD_RESET), [Features::getControllerClass(), 'resetPassword'])->name('reset-password.reset');
}

if (Features::isFeatureEnabled(Features::UPDATE_PASSWORD)) {
    Route::patch(Features::getUriFor(Features::UPDATE_PASSWORD), [Features::getControllerClass(), 'updatePassword'])->name('update-password')->middleware(config('fortress.routes.auth_middleware'));
}

if (Features::isFeatureEnabled(Features::UPDATE_PROFILE)) {
    Route::put(Features::getUriFor(Features::UPDATE_PROFILE), [Features::getControllerClass(), 'updateProfile'])->name('update-profile')->middleware(config('fortress.routes.auth_middleware'));
}