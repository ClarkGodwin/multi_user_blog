<?php

namespace App\Http\Controllers;

use App\Enums\ArticleStatusEnum;
use App\Http\Requests\Article\StoreArticleRequest;
use App\Http\Requests\Article\UpdateArticleRequest;
use App\Models\Article;
use Illuminate\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Inertia\Response;

class ArticleController extends Controller
{
    /**
     * it returns the paginated articles of the authenticated author corresponding to a specific status
     *
     * @return LengthAwarePaginator<int, Article>
     */
    private function articlesByStatus(string $status)
    {
        return Article::orderBy('updated_at', 'desc')
            ->where('user_id', auth()->user()->id)
            ->where('status', $status)
            ->select([
                'id',
                'title',
                'content',
                'created_at',
                'updated_at',
            ])
            ->paginate(10);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     *It renders the Published component with, as data, the paginated published articles created by the authenticated user who has to be an author
     *
     * @return Response
     */
    public function published()
    {
        $articles = $this->articlesByStatus(ArticleStatusEnum::Published->value);

        return Inertia::render('Articles/Published', compact('articles'));
    }

    /**
     *It renders the Craft component with, as data, the paginated craft articles created by the authenticated user who has to be an author
     *
     * @return Response
     */
    public function craft()
    {
        $articles = $this->articlesByStatus(ArticleStatusEnum::Craft->value);

        return Inertia::render('Articles/Craft', compact('articles'));
    }

    /**
     *It renders the Archived component with, as data, the paginated Archived articles created by the authenticated user who has to be an author
     *
     * @return Response
     */
    public function archived()
    {
        $articles = $this->articlesByStatus(ArticleStatusEnum::Archived->value);

        return Inertia::render('Articles/Archived', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Articles/CreateArticleForm');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        dd($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        //
    }
}
