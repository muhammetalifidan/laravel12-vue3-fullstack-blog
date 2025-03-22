<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

        $post = Post::create([
            'user_id' => auth()->user()->id,
            'title' => $validatedData['title'],
            'content' => $validatedData['content'],
            'published_at' => $validatedData['published_at'],
            'status' => $validatedData['status'],
        ]);

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
    public function show(Post $post)
    {
        //
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
    public function update(UpdatePostRequest $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
