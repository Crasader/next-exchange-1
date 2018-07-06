<?php

namespace App\Transformers;

use App\Models\ArticleComment;
use League\Fractal\TransformerAbstract;

class ArticleCommentTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'author',
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(ArticleComment $comment)
    {
        return [
            'id'            => $comment->id,
            'text'          => htmlspecialchars($comment->text),
            'human_date'    => $comment->created_at->diffForHumans(),
            'created_at'    => $comment->created_at,
            'updated_at'    => $comment->updated_at
        ];
    }

    public function includeAuthor(ArticleComment $comment)
    {
        return $this->item($comment->author, new UserTransformer());
    }
}
