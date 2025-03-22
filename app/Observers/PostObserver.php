<?php

namespace App\Observers;

use App\Events\PostCreated;
use App\Events\PostDeleted;
use App\Events\PostUpdated;
use App\Models\Post;
use Illuminate\Support\Str;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        event(new PostCreated($post));

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
        event(new PostUpdated($post));

        activity()
            ->performedOn($post)
            ->withProperties([
                'user_id' => $post->user_id,
                'title' => $post->title,
                'content' => $post->content,
                'published_at' => $post->published_at,
                'status' => $post->status,
            ])
            ->log('Post updated');
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        event(new PostDeleted($post));

        activity()
            ->performedOn($post)
            ->log('Post deleted');
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
