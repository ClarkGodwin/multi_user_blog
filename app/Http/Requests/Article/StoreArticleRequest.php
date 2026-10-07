<?php

namespace App\Http\Requests\Article;

use App\Models\Article;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return true;
        return $this->user()->can("create", Article::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "title"=> [
                'alpha_num',
                'required',
                'min:3',
                'max:100',
            ],

            "content" => [
                'alpha_num',
                'required',
            ],

            "status" => [
                'string',
                'required'
            ]
        ];
    }
}
