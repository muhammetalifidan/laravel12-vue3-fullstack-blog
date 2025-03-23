<?php

namespace App\Observers;

use App\Events\CommentCreated;
use App\Events\CommentDeleted;
use App\Events\CommentUpdated;
use App\Models\Comment;
use App\Notifications\NewCommentNotification;

class CommentObserver
{
    /**
     * Handle the Comment "created" event.
     */
    public function created(Comment $comment): void
    {
        event(new CommentCreated($comment));

        $author = $comment->post->user;
        $author->notify(new NewCommentNotification($comment));

        activity()
            ->performedOn($comment)
            ->withProperties([
                'user_id' => $comment->user_id,
                'post_id' => $comment->post_id,
                'body' => $comment->body,
                'status' => $comment->status,
            ])
            ->log('Comment created');
    }

    /**
     * Handle the Comment "updated" event.
     */
    public function updated(Comment $comment): void
    {
        event(new CommentUpdated($comment));

        $changedAttributes = $comment->getChanges();
        unset($changedAttributes['updated_at']);

        activity()
            ->performedOn($comment)
            ->withProperties($changedAttributes)
            ->log('Comment updated');
    }

    /**
     * Handle the Comment "deleted" event.
     */
    public function deleted(Comment $comment): void
    {
        event(new CommentDeleted($comment));

        activity()
            ->performedOn($comment)
            ->log('Comment deleted');
    }

    /**
     * Handle the Comment "restored" event.
     */
    public function restored(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "force deleted" event.
     */
    public function forceDeleted(Comment $comment): void
    {
        //
    }
}
