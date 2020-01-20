<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Auth\AuthenticationException;
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\Passport;
use Laravel\Passport\PersonalAccessTokenResult;

trait HasApiTokensWithName
{
    use HasApiTokens;

    public function getTokenName()
    {
        return $this->tokenName;
    }

    public function tokens()
    {
        return $this->hasMany(Passport::tokenModel(), 'user_id')
            ->where('name', $this->getTokenName())
            ->orderBy('created_at', 'desc');
    }

    public function withAccessToken($accessToken)
    {
        if ($accessToken->name !== $this->getTokenName()) {
            throw new AuthenticationException();
        }

        $this->accessToken = $accessToken;

        return $this;
    }

    public function saveToken(): PersonalAccessTokenResult
    {
        $token = $this->createToken($this->getTokenName());
        $token->token->expires_at = Passport::$tokensExpireAt;
        $token->token->saveOrFail();

        return $token;
    }
}