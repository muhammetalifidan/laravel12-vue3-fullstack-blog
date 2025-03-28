<?php

namespace App\Http\Requests;

use App\Enums\PostStatusType;
use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'published_at' => ['required', 'date'],
            'status' => ['required', 'string', Rule::enum(PostStatusType::class)],
            'category_ids' => ['required', 'array'],
            'category_ids.*' => ['exists:categories,id'],
        ];
    }
}
