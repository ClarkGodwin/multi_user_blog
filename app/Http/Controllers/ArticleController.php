<?php

namespace App\Http\Controllers;

use App\Enums\ArticleStatusEnum;
use App\Http\Requests\Article\StoreArticleRequest;
use App\Http\Requests\Article\UpdateArticleRequest;
use App\Models\Article;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
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
            ->withCount('comments')
            ->paginate(10)
            ->onEachSide(1)
            ->withQueryString();
    }

    /**
     * Display the details of an article.
     */
    public function details(int $id)
    {
        $article = Article::find($id);
        $userAuthor = $article->user()->select([
            'id',
            'name',
            'avatar',
        ])->first();
        $comments = $article->comments();
        $article = Article::where('id', $id)->withCount('comments')->first();

        return Inertia::render('Articles/ArticleDetails', compact(['article', 'userAuthor', 'comments']));
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
        return Inertia::render('Articles/Create');
    }

    /**
     * Store a newly created resource in storage.
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
     */
    public function edit($id)
    {
        $article = Article::find($id, [
            'id',
            'title',
            'content',
            'status',
        ]);

        return Inertia::render('Articles/Edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request)
    {
        $validated = $request->validated();
        $article = Article::find($validated['id']);
        $article->update(collect($validated)->except('id')->all());

        Inertia::flash('success', 'The Article was successfully updated');

        return redirect()->route('dashboard');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $article = Article::findOrFail($id);
        if (Auth::user()->can('delete', $article)) {
            $article->delete();

            Inertia::flash('success', 'Article successfully deleted');
            return redirect()->route('dashboard');
        }

        else{
            Inertia::flash('error', "You are not authorized to delete this article");

            return redirect()->back();
        }
    }
}
