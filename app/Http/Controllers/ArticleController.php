<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

use App\Models\Article;

use App\Http\Resources\ArticleResource;
use App\Http\Requests\ArticleCreateRequest;
use App\Http\Requests\ArticleUpdateRequest;

class ArticleController extends Controller
{
    public function postArticle(ArticleCreateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $article = Article::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'user_id' => $request->user()->id
        ])->load('user');
        return (new ArticleResource($article))->response()->setStatusCode(201);
    }

    public function getAllArticles()
    {
        return ArticleResource::collection(Article::with('user')->get());
    }

    public function getArticle(string $id): ArticleResource
    {
        $article = Article::with('user')->findOrFail($id);
        
        if($article->user_id !== Auth::id()) {
            throw new HttpResponseException(
                response()->json([
                    'error' => [
                        'message' => ['unauthorized']
                    ]
                    ], 403)
            );
        }
        
        return new ArticleResource($article);
    }

    public function updateArticleById(ArticleUpdateRequest $request, string $id): ArticleResource
    {
        $article = Article::findOrFail($id);

        if($article->user_id !== Auth::id()) {
            throw new HttpResponseException(
                response()->json([
                    'errors' => [
                        'message' => ['unauthorized']
                    ]
                    ], 403)
                );
        }

        $article->update($request->validated());
        $article->load('user');

        return new ArticleResource($article);
    }

    public function deleteArticleById(Request $request, string $id): JsonResponse
    {
        $article = Article::findOrFail($id);

        if ($article->user_id !== Auth::id()) {
            throw new HttpResponseException(
                response()->json([
                    'errors' => [
                        'message' => ['unauthorized']
                    ]
                ], 403)
            );
        }

        $article->delete();

        return response()->json([
            'data' => true
        ], 200);
    }
}