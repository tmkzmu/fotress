<?php

namespace Tmkzmu\Fortress\Traits;

use Str;

trait VerifyEmailTrait
{
    public function storeEmailVerificationToken()
    {
        $this->token = $this->generateToken();
        $this->save();
    }

    protected function generateToken()
    {
        return Str::random(40);
    }

    public function getToken()
    {
        return $this->token;
    }
}
