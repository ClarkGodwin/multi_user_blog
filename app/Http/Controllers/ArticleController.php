<?php

namespace App\Http\Controllers;

use App\Enums\ArticleStatusEnum;
use App\Http\Requests\Article\StoreArticleRequest;
use App\Http\Requests\Article\UpdateArticleRequest;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
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
     *
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
     *
     * @return Response
     */
    public function create()
    {
        return Inertia::render('Articles/Create');
    }

    /**
     * This is a function to create tags from an article.
     * It's meant to be used in both the store and  update functions inside  this controller
     * @param Article $article
     * @return void
     */
    private function createTagsFromAnArticle(Article $article)
    {
        /**
         * it searches inside the content of the article as a parameter and look for '#', it specifies the beginning of the name of the tag, the end is either another '#'(witch also specifies the beginning of another tag  name) or a whitespace.
         *
         * it searches inside $article->content and put the result in $tagNames which is an array of  arrayes containg 2 elements. The first one is the result of the search but each string is prefixed with '#' which is why we won't use  it since the prefix won't be needed to insert the name create the tag and this is when the second element comes and  why I used $tagNames[1]
         */
        preg_match_all('/#([^\s#]+)/u', $article->content, $tagNames);

        $tagIds = collect($tagNames[1])
            ->unique() // to remove  duplicates in $tagNames[1] case there are some

            /**
             * It first check if the tag already exist in the database and if not, it creates it with the id of the author of the article as the user_id
             */
            ->map(function (string $TagName) use ($article) {
                $tag = Tag::query()
                    ->where('name', $TagName)
                    ->first();

                if ($tag === null) {
                    $tag = $article->user->tags()->create([
                        'name' => $TagName,
                    ]);
                }

                return $tag->id;
            })
            ->all();

        /**
         * This insert each tag id inside the article_tag table with the id of the article as the article_id since the tags and articles tables have a many to many relationship
         *
         * Check the Tag and Article Model classes for more details
         */
        $article->tags()->sync($tagIds);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function store(StoreArticleRequest $request)
    {
        $article = $request->user()->articles()->create($request->validated());

        $this->createTagsFromAnArticle($article);

        Inertia::flash('success', 'The article was successfully created');

        //to redirect in published, craft or archived page depending on which status the user gave his article. Check web.php for more details
        return redirect()->route('article.'. $article->status);
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
     *
     * @return Response
     */
    public function edit(Article $article)
    {
        return Inertia::render('Articles/Edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return RedirectResponse
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        $validated = $request->validated();
        $article->update(collect($validated)->except('id')->all());

        $this->createTagsFromAnArticle($article);

        Inertia::flash('success', 'The Article was successfully updated');

        return redirect()->route('article.details', $article);
    }

    /**
     * Remove the specified resource from storage.
     *
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
