<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Category;
use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleCategoryFactory extends Factory
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
            "category_id" => Category::inRandomOrder('id')->first()->id,
        ];
    }
}
