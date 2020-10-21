<?php

namespace App\Auth\Guards;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class OauthGuard implements Guard
{
    use GuardHelpers;

    /**
     * @var Request
     */
    protected $request;

    /**
     * OauthGuard constructor.
     * @param UserProvider $userProvider
     * @param Request $request
     */
    public function __construct(UserProvider $userProvider, Request $request)
    {
        $this->provider = $userProvider;
        $this->request  = $request;
    }

    /**
     * @inheritDoc
     */
    public function validate(array $credentials = [])
    {
        if (empty($credentials['token'])) {
            return false;
        }

        if ($this->provider->retrieveByCredentials($credentials)) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function user()
    {
        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (! is_null($this->user)) {
            return $this->user;
        }

        $user = null;

        $token = $this->getTokenForRequest();

        if (!empty($token)) {
            $user = $this->provider->retrieveByCredentials(['token' => $token]);
        }

        return $this->user = $user;
    }

    /**
     * Get the token for the current request.
     *
     * @return string|null
     */
    protected function getTokenForRequest(): ?string
    {
        $token = $this->request->bearerToken();

        if (empty($token)) {
            $token = $this->request->getPassword();
        }

        return $token;
    }
}
