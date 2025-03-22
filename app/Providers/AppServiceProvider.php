<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Observers\CategoryObserver;
use App\Observers\CommentObserver;
use App\Observers\PostObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });

        Category::observe(CategoryObserver::class);
        Post::observe(PostObserver::class);
        Comment::observe(CommentObserver::class);
    }
}
