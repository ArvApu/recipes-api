<?php

namespace App\Auth\Guards;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Token;

class OauthGuard implements Guard
{
    use GuardHelpers;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var Parser
     */
    protected $tokenParser;

    /**
     * @var Token
     */
    protected $token;

    /**
     * OauthGuard constructor.
     * @param UserProvider $userProvider
     * @param Request $request
     * @param Cache $cache
     * @param Parser $tokenParser
     */
    public function __construct(UserProvider $userProvider, Request $request, Cache $cache, Parser $tokenParser)
    {
        $this->provider = $userProvider;
        $this->request  = $request;
        $this->cache = $cache;
        $this->tokenParser = $tokenParser;
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
        /* If there exists currently retrieved user for the current request we can just return it back. */
        if (! is_null($this->user)) {
            return $this->user;
        }

        $token = $this->getTokenForRequest();

        /* Without token it is impossible to get user so we skip further logic and return null. */
        if (empty($token)) {
            return null;
        }

        $tokenId = $this->getTokenId($token);

        /* If token has no id it indicates that it is invalid jwt token and therefore we won't be able to get user. */
        if(is_null($tokenId)) {
            return null;
        }

        if ($user = $this->cache->get($tokenId.':user')) {
            return $user;
        }

        $user = $this->provider->retrieveByCredentials(['token' => $token]);

        if(!is_null($user)) {
            $this->cache->set($tokenId.':user', $user, 600);
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

    /**
     * Get token jti
     *
     * @param string $token
     * @return string|null
     */
    protected function getTokenId(string $token): ?string
    {
        $token = $this->parseToken($token);

        if(is_null($token)) {
            return null;
        }

        return $token->getClaim('jti');
    }

    /**
     * Get token used for auth
     *
     * @param string $token
     * @return Token|null
     */
    protected function parseToken(string $token): ?Token
    {
        if($this->token) {
            return $this->token;
        }

        try {
            $this->token = $this->tokenParser->parse($token);
        } catch (InvalidArgumentException $exception) {
            return null;
        }

        return $this->token;
    }
}
