<?php

namespace App\Http\Requests\Article;

class ArticleRules
{
    public static function get(): array
    {
        return [
            'title' => [
                'string',
                'required',
                'min:3',
                'max:100',
            ],

            'content' => [
                'string',
                'required',
                'min:10',
            ],

            'status' => [
                'string',
                'required',
            ],
        ];
    }
}
