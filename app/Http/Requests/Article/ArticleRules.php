<?php

namespace App\Http\Requests\Article;

use App\Enums\ArticleStatusEnum;
use Illuminate\Validation\Rule;

class ArticleRules
{
    public static function get(): array
    {
        $rules = [
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
                Rule::enum(ArticleStatusEnum::class),
                'required',
            ],
        ];

        return $rules;
    }
}
