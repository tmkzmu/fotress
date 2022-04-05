<?php

namespace App\Actions\Fortress;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PasswordReset
{
    public function askForResetEmail(array $input)
    {
        $validated = Validator::make($input, [
            'email' => 'required|email',
        ])->validate();

        $status = Password::sendResetLink($validated);

        return $status === Password::RESET_LINK_SENT
            ? response(__($status), Response::HTTP_OK)
            : response(__($status), Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function resetPassword(array $input)
    {
        Validator::make($input, [
            'token'    => ['required'],
            'email'    => ['required', 'max:255', 'email'],
            'password' => ['required', 'min:10', 'max:255', 'confirmed'],
        ])->validate();

        $entity = null;

        $status = Password::reset(
            request()->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) use (& $entity) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new \Illuminate\Auth\Events\PasswordReset($entity = $user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return response(__($status), Response::HTTP_OK);
        }
        return response(['access_token' => $entity->createToken('web_api')->plainTextToken]);
    }
}
