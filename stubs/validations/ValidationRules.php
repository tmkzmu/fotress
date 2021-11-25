<?php

namespace App\Actions\Fortress\Validations;

use App\Models\User;
use Illuminate\Validation\Rule;

class ValidationRules
{
    public static function email()
    {
        return [
            'required',
            'string',
            'email',
            'max:255',
            Rule::unique(User::class),
        ];
    }

    public static function password()
    {
        return [
            'required',
            'min:8',
            'confirmed'
        ];
    }
}
