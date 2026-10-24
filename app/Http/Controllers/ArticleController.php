<?php

namespace App\Http\Controllers;

use App\Enums\ArticleStatusEnum;
use App\Http\Requests\Article\StoreArticleRequest;
use App\Http\Requests\Article\UpdateArticleRequest;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ArticleController extends Controller
{
    /**
     * it returns the paginated articles of the authenticated author corresponding to a specific status
     * @param string $status
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
            ->withCount('comments')
            ->paginate(10)
            ->onEachSide(1)
            ->withQueryString();
    }

    /**
     * Display the details of an article.
     * @param Article $article
     * @return Response
     */
    public function details(Article $article)
    {
        $canTheAuthenticatedUserUpdateTheArticle = Auth::user()->can('update', $article);

        $canTheAuthenticatedUserDeleteTheArticle = Auth::user()->can('update', $article);

        $userAuthor = $article->user()->select([
            'id',
            'name',
            'avatar',
        ])->first();

        $comments = $article->comments()
            ->orderBy('created_at', 'desc')
            ->with('user:id,name,avatar')
            ->select(['id', 'article_id', 'user_id', 'content', 'created_at'])
            ->paginate(10)
            ->onEachSide(1)
            ->withQueryString();

        $article = $article->loadCount('comments');

        return Inertia::render('Articles/ArticleDetails', compact([
            'article',
            'userAuthor',
            'comments',
            'canTheAuthenticatedUserUpdateTheArticle',
            'canTheAuthenticatedUserDeleteTheArticle',
        ]));
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
     * @return Response
     */
    public function create()
    {
        return Inertia::render('Articles/Create');
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreArticleRequest $request
     * @return RedirectResponse
     */
    public function store(StoreArticleRequest $request)
    {
        $request->user()->articles()->create($request->validated());

        Inertia::flash('success', 'The article was successfully created');

        return redirect()->route('dashboard');
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
     * @param Article $article
     * @return Response
     */
    public function edit(Article $article)
    {
        return Inertia::render('Articles/Edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateArticleRequest $request
     * @param Article $article
     * @return RedirectResponse
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        $validated = $request->validated();
        $article->update(collect($validated)->except('id')->all());

        Inertia::flash('success', 'The Article was successfully updated');

        return redirect()->route('dashboard');
    }

    /**
     * Remove the specified resource from storage.
     * @param Article $article
     * @return RedirectResponse
     */
    public function destroy(Article $article)
    {
        if (Auth::user()->can('delete', $article)) {
            $article->delete();

            Inertia::flash('success', 'Article successfully deleted');

            return redirect()->route('dashboard');
        } else {
            Inertia::flash('error', 'You are not authorized to delete this article');

            return redirect()->back();
        }
    }
}
