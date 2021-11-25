<?php

namespace App\Actions\Fortress;

use App\Actions\Fortress\Validations\ValidationRules;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class UpdateProfile
{
    public function update(array $input)
    {
        $validated = Validator::make($input, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ValidationRules::email(),
        ])->validate();

        try {
            request()->user()->update($validated);
            return response('', Response::HTTP_OK);
        } catch (\Throwable $e) {
            return response('', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
