<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $content
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['title', 'content'])]
class Article extends Model
{
    /** @use HasFactory<\Database\Factories\ArticleFactory> */
    use HasFactory;

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function comments() : HasMany {
        return $this->hasMany(Comment::class);
    }

    public function categories() : BelongsToMany {
        return $this->belongsToMany(Category::class);
    }

    public function tags() : BelongsToMany {
        return $this->belongsToMany(Tag::class);
    }
}
