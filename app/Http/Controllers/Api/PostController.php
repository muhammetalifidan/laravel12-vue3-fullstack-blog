<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
        $validatedData = $request->validated();
        $validatedData['image'] = $request->file('image')->store('images');

        DB::transaction(function () use ($validatedData) {
            $post = Post::create([
                'title' => $validatedData['title'],
                'content' => $validatedData['content'],
                'image' => $validatedData['image'],
                'published_at' => $validatedData['published_at'],
                'status' => $validatedData['status'],
                'user_id' => auth()->user()->id,
            ]);

            $post->update(['slug' => Str::slug($validatedData['title'] . '-' . $post->id)]);
        });

        return response()->json([
            'message' => 'Post created successfully',
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
