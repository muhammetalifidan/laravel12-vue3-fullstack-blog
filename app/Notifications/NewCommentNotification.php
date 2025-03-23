<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification
{
    use Queueable;

    protected $comment;

    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $post = $this->comment->post;

        return (new MailMessage)
            ->subject('Yeni yorum: ' . $post->title)
            ->line($this->comment->user->name . ' yazınıza yorum yaptı.')
            ->line('Yorum: ' . $this->comment->body)
            ->action('Yorumu görüntüle', route('posts.comments.show', ['post' => $post->id, 'comment' => $this->comment->id]));
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'comment_id' => $this->comment->id,
            'user_id' => $this->comment->user_id,
            'post_id' => $this->comment->post_id,
            'body' => $this->comment->body,
            'status' => $this->comment->status,
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'comment_id' => $this->comment->id,
            'user_id' => $this->comment->user_id,
            'post_id' => $this->comment->post_id,
            'body' => $this->comment->body,
            'status' => $this->comment->status,
            'time' => now()->diffForHumans()
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
