<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Str;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        $post->update(['slug' => Str::slug($post->title . '-' . $post->id)]);

        activity()
            ->performedOn($post)
            ->withProperties([
                'user_id' => $post->user_id,
                'title' => $post->title,
                'content' => $post->content,
                'published_at' => $post->published_at,
                'status' => $post->status,
            ])
            ->log('Post created');
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
        //
    }
}
