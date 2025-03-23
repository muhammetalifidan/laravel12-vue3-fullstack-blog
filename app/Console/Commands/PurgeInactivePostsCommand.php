<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class PurgeInactivePostsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:purge-inactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge inactive posts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Post::whereDoesntHave('comments')
            ->where('created_at', '<=', now()->subWeek())
            ->each(function ($post) {
                $post->categories()->detach();
                $post->delete();
            });

        activity()->log('Inactive posts purged');
    }
}
