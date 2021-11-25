<?php

namespace Tmkzmu\Fortress\Controllers;

use App\Actions\Fortress\EmailVerification;
use App\Actions\Fortress\Login;
use App\Actions\Fortress\PasswordReset;
use App\Actions\Fortress\Register;
use App\Actions\Fortress\UpdatePassword;
use App\Actions\Fortress\UpdateProfile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class FortressController extends BaseController implements FortressControllerInterface
{
    public function register(Request $request, Register $action)
    {
        $user = $action->register($request->all());
        event(new Registered($user));
        return $action->response();
    }

    public function login(Request $request, Login $action)
    {
        return $action->login($request->all());
    }

    public function logout(Request $request, Login $action)
    {
        $action->logout();
    }

    public function askEmailVerificationEmail(Request $request, EmailVerification $action)
    {
        return $action->askForAnEmail($request->user());
    }

    public function emailVerification(Request $request, EmailVerification $action)
    {
        return $action->verify($request->all());
    }

    public function askResetPassword(Request $request, PasswordReset $action)
    {
        return $action->askForResetEmail($request->all());
    }

    public function resetPassword(Request $request, PasswordReset $action)
    {
        return $action->resetPassword($request->all());
    }

    public function updatePassword(Request $request, UpdatePassword $action)
    {
        return $action->update($request->all());
    }

    public function updateProfile(Request $request, UpdateProfile $action)
    {
        return $action->update($request->all());
    }
}
