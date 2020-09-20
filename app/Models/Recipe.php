<?php

namespace App\Models;

/**
 * Class Recipe
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $description
 * @property string $recipe
 * @property $created_at
 * @property $updated_at
 * @property User $author
 * @property Comment[] $comments
 * @package App\Models
 */
class Recipe extends Model
{
    /**
     * @inheritdoc
     */
    protected $fillable = [
        'name', 'user_id', 'description', 'recipe'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'users');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class);
    }
}