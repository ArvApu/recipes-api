<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class User
 * @property integer $id
 * @property string $role_id
 * @property string $email
 * @property string|null $username
 * @property $last_login_at
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 * @property Recipe[] $recipes
 * @property Comment[] $comments
 * @package App\Models
 */
class User extends Model
{
    use SoftDeletes;

    /**
     * @inheritdoc
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($instance) {
            if(!isset($instance->role_id)) {
                $instance->role_id = Role::USER;
            }
        });
    }

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'email', 'username', 'last_login_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recipes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Recipe::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class);
    }
    /**
     * @return BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

}
