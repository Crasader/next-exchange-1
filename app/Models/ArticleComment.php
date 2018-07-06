<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleComment extends Model
{
    use SoftDeletes;

    protected $table = 'articles_comments';

    protected $fillable = [
        'user_id',
        'article_id',
        'text'
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

    public function article() : BelongsTo
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
}
