<?php

namespace App\Actions\Fortress;

use App\Actions\Fortress\Validations\ValidationRules;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UpdatePassword
{
    public function update(array $input)
    {
        $validated = Validator::make($input, [
            'password' => ValidationRules::password(),
        ])->validate();

        request()->user()->password = Hash::make($validated['password']);
        request()->user()->save();

        return response('', Response::HTTP_OK);
    }
}
