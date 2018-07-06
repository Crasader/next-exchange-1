<?php

namespace App\Transformers;

use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use League\Fractal\TransformerAbstract;

class ArticleTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'author',
        'comments'
    ];

    protected function prepareText ($text) {
        return nl2br(htmlspecialchars($text));
    }

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Article $article) : array
    {
        $response = [
            'id'                => $article->id,
            'text'              => $article->text,
            'type'              => $article->type,
            'image_url'         => Storage::url($article->image_path),
            'comments_count'    => (int) $article->comments_count,
            'likes_count'       => (int) $article->likes_count,
            'human_date'        => $article->created_at->diffForHumans(),
            'created_at'        => $article->created_at,
            'updated_at'        => $article->updated_at,
        ];

        $user = Auth::user();

        if ($user->relationLoaded('articlesLikes')) {
            $response['is_liked'] = $user->articlesLikes
                ->where('id', $article->id)
                ->count() > 0;
        }

        return $response;
    }

    public function includeAuthor(Article $article)
    {
        return $this->item($article->author, new UserTransformer());
    }

    public function includeComments(Article $article)
    {
        return $this->collection($article->comments, new ArticleCommentTransformer());
    }
}
