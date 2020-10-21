<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

final class AuthorizationServer
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var Client
     */
    private $http;

    /**
     * @var string
     */
    private $uri;

    /**
     * AuthorizationServerServiceProvider constructor.
     * @param string $tokenUri
     */
    public function __construct(string $tokenUri)
    {
        $this->uri  = $tokenUri;
        $this->data = [];
        $this->http = new Client([
            'headers' => ['Accept' => 'application/json'],
        ]);
    }

    /**
     * Get user information with token
     *
     * @param string $token
     * @return array|null
     */
    public function getUserInformationWithToken(string $token): ?array
    {
        try {
            $response = $this->http->get($this->uri, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ]
            ]);
        } catch (GuzzleException $e) {
            return null;
        }

        $data = $response->getBody()->getContents();

        return $this->data = json_decode($data, true);
    }
}
