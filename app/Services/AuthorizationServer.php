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
     * AuthorizationServerServiceProvider constructor.
     */
    public function __construct()
    {
        $this->data = [];
        $this->http = new Client([
            'base_uri' => 'http://localhost:8000/',
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
            $response = $this->http->get('/api/user', [
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
