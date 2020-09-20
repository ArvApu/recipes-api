<?php

namespace App\Models;

/**
 * Class User
 * @property integer $id
 * @property string $email
 * @property string|null $username
 * @property $last_login_at
 * @property $created_at
 * @property $updated_at
 * @property Recipe[] $recipes
 * @property Comment[] $comments
 * @package App\Models
 */
class User extends Model
{
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
}
