<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use SoftDeletes;

    protected $table = 'articles';

    protected $fillable = [
        'text',
        'type',
        'user_id',
        'image_path'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function author() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments() : HasMany
    {
        return $this->hasMany(ArticleComment::class, 'article_id');
    }

    public function likes() : BelongsToMany
    {
        return $this->belongsToMany(User::class,
            'articles_has_likes',
        'article_id',
        'user_id'
        );
    }
}
