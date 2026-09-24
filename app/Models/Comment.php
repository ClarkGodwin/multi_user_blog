<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int $article_id
 * @property string $content
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['article_id', 'content'])]
class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function article() : BelongsTo {
        return $this->belongsTo(Article::class);
    }
}
