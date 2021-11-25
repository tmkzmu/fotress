<?php

namespace App\Actions\Fortress;

use App\Actions\Fortress\Validations\ValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class Register
{
    protected User $user;

    public function register(array $input)
    {
        Validator::make($input, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ValidationRules::email(),
            'password' => ValidationRules::password(),
        ])->validate();

        $this->user = User::create([
            'name'     => $input['name'],
            'email'    => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        return $this->user;
    }

    public function response()
    {
        return $this->user;
    }
}
