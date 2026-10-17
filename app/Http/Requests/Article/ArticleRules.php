<?php

namespace App\Http\Requests\Article;

use App\Enums\ArticleStatusEnum;
use Illuminate\Validation\Rule;

class ArticleRules
{
    public static function get(bool $isUpdate = false): array
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

        if ($isUpdate) {
            $rules['id'] = [
                'required',
                'integer',
                'exists:articles,id'
            ];
        }
    }
}
