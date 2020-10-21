<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Role
 * @property string $id
 * @property string $description
 * @package App\Models
 */
class Role extends Model
{
    const ADMIN = 'admin';
    const USER  = 'user';

    /**
     * @inheritdoc
     */
    protected $table = 'roles';

    /**
     * @inheritdoc
     */
    public $incrementing = false;

    /**
     * @inheritdoc
     */
    public $timestamps = false;

    /**
     * Get all users that belongs to this role.
     *
     * @return HasMany
     */
    public function users()
    {
        return $this->hasMany(Role::class, 'role_id');
    }
}
