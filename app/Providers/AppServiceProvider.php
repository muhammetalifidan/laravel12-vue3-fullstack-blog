<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Observers\CategoryObserver;
use App\Observers\CommentObserver;
use App\Observers\PostObserver;
use App\Observers\UserObserver;
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

        User::observe(UserObserver::class);
        Category::observe(CategoryObserver::class);
        Post::observe(PostObserver::class);
        Comment::observe(CommentObserver::class);
    }
}
