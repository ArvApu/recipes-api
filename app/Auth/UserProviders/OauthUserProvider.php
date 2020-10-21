<?php

namespace App\Auth\UserProviders;

use App\Models\Role;
use App\Models\User;
use App\Services\AuthorizationServer;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Database\ConnectionInterface;

class OauthUserProvider implements UserProvider
{
    /**
     * @var AuthorizationServer
     */
    protected $authServer;

    /**
     * @var ConnectionInterface
     */
    protected $conn;

    /**
     * @var string
     */
    protected $table;

    /**
     * OauthUserProvider constructor.
     * @param AuthorizationServer $authServer
     * @param ConnectionInterface $conn
     * @param string $table
     */
    public function __construct(AuthorizationServer $authServer, ConnectionInterface $conn, string $table)
    {
        $this->authServer = $authServer;
        $this->conn = $conn;
        $this->table = $table;
    }

    /**
     * @inheritDoc
     */
    public function retrieveById($identifier)
    {
        $user = $this->conn->table($this->table)->find($identifier);

        if(is_null($user)) {
            return null;
        }

        return (new User())->forceFill((array) $user);
    }

    /**
     * @inheritDoc
     */
    public function retrieveByToken($identifier, $token)
    {
        // TODO: Implement retrieveByToken() method.
    }

    /**
     * @inheritDoc
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        // TODO: Implement updateRememberToken() method.
    }

    /**
     * @inheritDoc
     */
    public function retrieveByCredentials(array $credentials)
    {
        $oauthUser = $this->authServer->getUserInformationWithToken($credentials['token'] ?? '');

        if(is_null($oauthUser)) {
            return null;
        }

        $user = $this->resolveUser($oauthUser);

        return (new User())->forceFill($user);
    }

    /**
     * @inheritDoc
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // TODO: Implement validateCredentials() method.
    }

    /**
     * Resolve system user with oauth user information
     *
     * @param array $oauthUser
     * @return array
     */
    protected function resolveUser(array $oauthUser): array
    {
        $systemUser = $this->conn->table($this->table)->where('oauth_user_id', '=', $oauthUser['id'])->first();

        if(!is_null($systemUser)) {
            return (array) $systemUser;
        }

        $systemUser = [
            'role_id' => $this->resolveUserRole($oauthUser['role_id']),
            'oauth_user_id' => $oauthUser['id'],
            'username' => $oauthUser['username'],
            'email' => $oauthUser['email'],
            'last_login_at' => new \DateTime(),
            'created_at' => new \DateTime(),
            'updated_at' => new \DateTime(),
        ];

        $systemUser['id'] = $this->conn->table($this->table)->insertGetId($systemUser);

        return $systemUser;
    }

    /**
     * Resolve user role from oauth user role
     *
     * @param string $oauthRole
     * @return string
     */
    protected function resolveUserRole(string $oauthRole): string
    {
        $role = Role::USER; // Default is simple user

        switch ($oauthRole) {
            case 'admin':
                $role = Role::ADMIN;
                break;
            case 'user':
                $role = Role::USER;
                break;
        }

        return $role;
    }
}
