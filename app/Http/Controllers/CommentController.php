<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use Auth;
use Inertia\Inertia;

class CommentController extends Controller
{
    /**
     * display the details of the comment
     */
    public function details(Comment $comment) {
        $canTheAuthenticatedUserUpdateTheComment = Auth::user()->can('update', $comment);
        $canTheAuthenticatedUserDeleteTheComment = Auth::user()->can('delete', $comment);

        return response()->json([
        'canTheAuthenticatedUserUpdateTheComment' => $canTheAuthenticatedUserUpdateTheComment,
        'canTheAuthenticatedUserDeleteTheComment' => $canTheAuthenticatedUserDeleteTheComment,
    ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        dd($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        if (Auth::user()->can('delete', $comment)) {
            $comment->delete();

            Inertia::flash('success', 'Comment successfully deleted');

        } else {
            Inertia::flash('error', 'You are not authorized to delete this article');
        }

        return redirect()->back();
    }
}
