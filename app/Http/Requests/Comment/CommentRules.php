<?php

namespace App\Http\Requests\Comment;

use Illuminate\Validation\Rule;

class CommentRules
{
    public static function get(): array
    {
        $rules = [
            'content' => [
                'string',
                'required',
                'min:10',
            ],
        ];

        return $rules;
    }
}
