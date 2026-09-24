<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Model;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleTagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "article_id" => Article::inRandomOrder('id')->first()->id,
            "tag_id" => Tag::inRandomOrder('id')->first()->id,
        ];
    }
}
