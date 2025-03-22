<?php

namespace App\Observers;

use App\Events\CategoryCreated;
use App\Events\CategoryDeleted;
use App\Events\CategoryUpdated;
use App\Models\Category;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
        event(new CategoryCreated($category));

        activity()
            ->performedOn($category)
            ->withProperties([
                'user_id' => $category->user_id,
                'name' => $category->name,
                'slug' => $category->slug,
            ])
            ->log('Category created');
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $category): void
    {
        event(new CategoryUpdated($category));

        activity()
            ->performedOn($category)
            ->withProperties([
                'user_id' => $category->user_id,
                'name' => $category->name,
                'slug' => $category->slug,
            ])
            ->log('Category updated');
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        event(new CategoryDeleted($category));

        activity()
            ->performedOn($category)
            ->log('Category deleted');
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "force deleted" event.
     */
    public function forceDeleted(Category $category): void
    {
        //
    }
}
