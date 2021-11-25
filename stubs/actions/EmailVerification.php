<?php

namespace App\Actions\Fortress;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Response;

class EmailVerification
{
    public function askForAnEmail(User $user)
    {
        $user->storeEmailVerificationToken();
        $user->sendEmailVerificationNotification($user);
        return response('', Response::HTTP_OK);
    }

    public function verify(array $input)
    {
        if ($user = User::whereNull('email_verified_at')->whereToken($input['token'])->first()) {
            $user->markEmailAsVerified();

            event(new Verified($user));

            return response('', Response::HTTP_OK);
        }

        return response('', Response::HTTP_FORBIDDEN);
    }
}
