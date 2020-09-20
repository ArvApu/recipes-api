<?php

namespace App\Models;

/**
 * Class Comment
 * @property integer $id
 * @property integer $user_id
 * @property integer $recipe_id
 * @property string $title
 * @property string $comment
 * @property $created_at
 * @property $updated_at
 * @property User $author
 * @property Recipe $recipe
 * @package App\Models
 */
class Comment extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'users');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recipe(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
