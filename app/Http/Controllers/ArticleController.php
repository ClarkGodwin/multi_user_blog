<?php

namespace App\Http\Controllers;

use App\Enums\ArticleStatusEnum;
use App\Http\Requests\Article\StoreArticleRequest;
use App\Http\Requests\Article\UpdateArticleRequest;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ArticleController extends Controller
{
    public function home()
    {
        $articles = Article::orderBy('updated_at', 'desc')
            ->select([
                'id',
                'user_id',
                'title',
                'content',
                'created_at',
                'updated_at',
            ])
            ->where('status', ArticleStatusEnum::Published->value)
            ->with('user:id,name,avatar')
            ->withCount('comments')
            ->paginate(30)
            ->onEachSide(1)
            ->withQueryString();

        return Inertia::render('Home', compact('articles'));
    }

    /**
     * This function is there to help with the search and tag suggestion features
     * The returned articles will have the status corresponding to the route where the request came from.
     * This makes the search reusable for all article's status instead of making a specific search function for every route where it's needed
     *
     * @param Request $request
     * @return string
     */
    private function statusCorrespondingToARequest(Request $request)
    {
        $url = $request->url();

        return match (true) {
            str_contains($url, 'craft') => ArticleStatusEnum::Craft->value,
            str_contains($url, 'published') => ArticleStatusEnum::Published->value,
            str_contains($url, 'home') => ArticleStatusEnum::Published->value,
            str_contains($url, 'archived') => ArticleStatusEnum::Archived->value,
            default => ArticleStatusEnum::Published->value,
        };
    }

    /**
     * To show the results of a search from an input
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'tag' => ['nullable', 'string', 'max:80'],
        ]);

        $statusFromTheRequest = $this->statusCorrespondingToARequest($request);

        $term = trim($filters['q'] ?? '');
        // the tag is considered as the word with the character '#' as a prefix
        $tag = trim($filters['tag'] ?? '');

        $articles = Article::query()
            ->where('status', $statusFromTheRequest)
            ->when($tag !== '', function (Builder $query) use ($tag) {
                // If it's a tag, it'll search among the articles with the relationship tags and check where the name corresponds  to the name of a tag in the database
                $query->whereHas('tags', function (Builder $tagQuery) use ($tag) {
                    $tagQuery->where('name', $tag);
                });
            })
            ->when($tag === '' && $term !== '', function (Builder $query) use ($term) {
                $query->where(function (Builder $textQuery) use ($term) {
                    $textQuery
                        ->where('title', 'like', "%{$term}%")
                        ->orWhere('content', 'like', "%{$term}%");
                });
            })
            ->select(['id', 'user_id', 'title', 'updated_at'])
            ->orderByDesc('updated_at')
            ->limit(8)
            ->get();

        return response()->json(['data' => $articles]);
    }

    /**
     * To show a suggestion of tag if the search starts with a '#'
     *
     * It shows suggestions based on the characters in the search, for a the suggestion to be returned, its name must contain the characters in the search, not necesseraly in the same order
     * @param Request $request
     * @return JsonResponse
     */
    public function tagSuggestions(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:80'],
        ]);

        $term = trim($filters['q'] ?? '');

        $tags = Tag::query()
            ->whereHas('articles', function (Builder $articleQuery) {
                $articleQuery->where('status', ArticleStatusEnum::Published->value);
            })
            ->when($term !== '', function (Builder $query) use ($term) {
                $query->where('name', 'like', "%{$term}%");
            })
            ->distinct()
            ->orderBy('name')
            ->limit(8)
            ->pluck('name');

        return response()->json(['data' => $tags]);
    }

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
            ->select(['id', 'article_id', 'user_id', 'content', 'updated_at'])
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
     *
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

        // to redirect in published, craft or archived page depending on which status the user gave his article. Check web.php for more details
        return redirect()->route('article.'.$article->status);
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
