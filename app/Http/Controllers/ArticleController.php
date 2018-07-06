<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Models\ArticleComment;
use Illuminate\Support\Facades\Auth;
use App\Transformers\ArticleTransformer;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use App\Transformers\ArticleCommentTransformer;
use App\Http\Requests\Article\CreateArticleRequest;
use App\Http\Requests\Article\PostArticleCommentRequest;

class ArticleController extends Controller
{

    private function stripText ($text) {
        return strip_tags($text, '<ol><ul><p><li><strong><em><br>');
    }

    /**
     * Create article and assign author
     *
     * @param CreateArticleRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(CreateArticleRequest $request)
    {
        $user = Auth::user();

        $article = new Article([
            'text'          => $this->stripText($request->text),
            'type'          => $request->type
        ]);

        if ($request->hasFile('image')) {
            $filename = Storage::disk('public')->put('articles', $request->file('image'));
            $article->image_path = $filename;
        }

        $article->author()->associate($user);

        $article->save();

        return transformModel($article, new ArticleTransformer())
            ->respond(Response::HTTP_CREATED);
    }

    /**
     * Fetches articles of certain user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listUserArticles(Request $request)
    {
        $request->id = $request->id ?: Auth::id();

        /**
         * Lazy load articles likes because there is a need
         * to determine which articles liked or not
         */
        Auth::user()->load('articlesLikes');

        /** @var User $user */
        $user = User::findOrFail($request->id);

        $articles = $user->articles()
            ->with([
                'author',
                'comments' => function($builder) {
                    $builder->orderBy('created_at', 'desc')
                        ->take(5);
                },
                'comments.author'
            ])
            ->withCount(['comments', 'likes'])
            ->orderBy('created_at', 'desc')
            ->paginate();

        return transformModel($articles, new ArticleTransformer())->respond();
    }

    /**
     * Creates comment for article
     *
     * @param PostArticleCommentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postComment(PostArticleCommentRequest $request)
    {
        $article = Article::findOrFail($request->article_id);

        $comment = new ArticleComment([
           'text'   => $request->text
        ]);

        $comment->article()->associate($article);
        $comment->author()->associate(Auth::user());
        $comment->save();

        return transformModel($comment, new ArticleCommentTransformer(), [], ['author'])
            ->respond();
    }

    /**
     * Toggle likes on specific article
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function like(Request $request)
    {
        /** @var Article $article */
        $article = Article::findOrFail($request->article_id);

        $hasLike = $article->likes()->where('user_id', Auth::id())->exists();

        if ($hasLike) {
            $article->likes()->detach([Auth::id()]);
        } else {
            $article->likes()->attach([Auth::id()]);
        }

        return response()
            ->json([
                'count'     => $article->likes()->count(),
                'is_liked'  => !$hasLike
            ], Response::HTTP_ACCEPTED);
    }
}
