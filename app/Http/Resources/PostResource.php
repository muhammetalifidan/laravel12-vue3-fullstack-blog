<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'image' => $this->getMedia()->first() ? $this->getMedia()->first()->getUrl() : null,
            'published_at' => $this->published_at->format('d-m-Y'),
            'status' => $this->status,
            'user' => $this->user->first_name . ' ' . $this->user->last_name,
            'categories' => CategoryResource::collection($this->categories),
            'comments_count' => $this->comments->count(),
            'comments' => CommentResource::collection($this->comments),
            'created_at' => $this->created_at->format('d-m-Y'),
        ];
    }
}
