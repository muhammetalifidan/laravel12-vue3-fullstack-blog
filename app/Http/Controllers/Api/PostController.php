<?php

namespace App\Http\Controllers\Api;

use App\Enums\PostStatusType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $posts = Post::all();

        return response()->json([
            'status' => true,
            'data' => PostResource::collection($posts)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        Gate::authorize('create', Post::class);

        $validatedData = $request->validated();

        DB::transaction(function () use ($validatedData, &$post) {
            $post = Post::create([
                'user_id' => auth()->user()->id,
                'title' => $validatedData['title'],
                'slug' => Str::slug($validatedData['title'] . '-' . time()),
                'content' => $validatedData['content'],
                'published_at' => $validatedData['published_at'],
                'status' => $validatedData['status'],
            ]);

            $post->categories()->attach($validatedData['category_ids']);
        });

        $post->addMediaFromRequest('image')->toMediaCollection();

        return response()->json([
            'status' => true,
            'message' => 'Post created successfully',
            'data' => new PostResource($post)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): JsonResponse
    {
        if ($post->status !== PostStatusType::PUBLISHED->value) {
            return response()->json(['status' => false, 'message' => 'Post not published'], 403);
        }

        return response()->json([
            'status' => true,
            'data' => new PostResource($post)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        Gate::authorize('update', $post);

        $validatedData = $request->validated();

        DB::transaction(function () use ($validatedData, $post) {
            $post->update([
                'title' => $validatedData['title'],
                'slug' => Str::slug($validatedData['title'] . '-' . time()),
                'content' => $validatedData['content'],
                'published_at' => $validatedData['published_at'],
                'status' => $validatedData['status'],
            ]);

            $post->categories()->sync($validatedData['category_ids']);
        });

        if ($request->hasFile('image')) {
            $post->clearMediaCollection();
            $post->addMediaFromRequest('image')->toMediaCollection();
        }

        return response()->json([
            'status' => true,
            'message' => 'Post updated successfully',
            'data' => new PostResource($post)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): JsonResponse
    {
        Gate::authorize('delete', $post);

        $post->clearMediaCollection();

        DB::transaction(function () use ($post) {
            $post->categories()->detach();
            $post->comments()->delete();
            $post->delete();
        });

        return response()->json([
            'status' => true,
            'message' => 'Post deleted successfully'
        ]);
    }
}
