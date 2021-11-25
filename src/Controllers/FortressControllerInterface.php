<?php

namespace Tmkzmu\Fortress\Controllers;

use App\Actions\Fortress\EmailVerification;
use App\Actions\Fortress\Login;
use App\Actions\Fortress\PasswordReset;
use App\Actions\Fortress\Register;
use App\Actions\Fortress\UpdatePassword;
use App\Actions\Fortress\UpdateProfile;
use Illuminate\Http\Request;

interface FortressControllerInterface
{
    public function register(Request $request, Register $action);

    public function login(Request $request, Login $action);

    public function logout(Request $request, Login $action);

    public function askEmailVerificationEmail(Request $request, EmailVerification $action);

    public function emailVerification(Request $request, EmailVerification $action);

    public function resetPassword(Request $request, PasswordReset $action);

    public function askResetPassword(Request $request, PasswordReset $action);

    public function updatePassword(Request $request, UpdatePassword $action);

    public function updateProfile(Request $request, UpdateProfile $action);
}
