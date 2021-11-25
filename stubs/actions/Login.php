<?php

namespace App\Actions\Fortress;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Tmkzmu\Fortress\Exceptions\LoginFailedException;

class Login
{
    public function login(array $input)
    {
        $validated = Validator::make($input, $this->rules())->validate();

        try {
            $this->authenticate($validated);
            $token = $this->getUser($validated['username'])->createToken('web_api');
            return response()->json(['access_token' => $token->plainTextToken], Response::HTTP_OK);
        } catch (LoginFailedException $e) {
            return response()->json(__('Incorrect credentials'), Response::HTTP_BAD_REQUEST);
        }
    }

    public function logout()
    {
        request()->user()->tokens()->delete();
    }

    protected function rules()
    {
        return [
            'username' => 'required|string',
            'password' => 'required|string',
        ];
    }

    protected function authenticate(array $userCredentials)
    {
        if (!Auth::attempt([
            'email'    => $userCredentials['username'],
            'password' => $userCredentials['password']
        ])) {
            throw new LoginFailedException();
        }
    }

    protected function getUser($email): User
    {
        return User::whereEmail($email)->first();
    }
}
