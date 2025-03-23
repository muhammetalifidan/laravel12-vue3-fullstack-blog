<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'post_id' => $this->post_id,
            'body' => $this->body,
            'status' => $this->status,
            'created_at' => $this->created_at->format('d-m-Y H:i:s'),
            'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
            'deleted_at' => $this->deleted_at?->format('d-m-Y H:i:s'),
            'user' => [
                'id' => $this->user_id,
                'first_name' => $this->user->first_name,
                'last_name' => $this->user->last_name,
                'email' => $this->user->email,
            ],
            'post' => [
                'id' => $this->post_id,
                'title' => $this->post->title,
                'slug' => $this->post->slug,
                'status' => $this->post->status,
            ]
        ];
    }
}
