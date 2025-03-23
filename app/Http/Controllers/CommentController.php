<?php

namespace App\Http\Controllers;

use App\Enums\CommentStatusType;
use App\Enums\PostStatusType;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Requests\UpdateCommentStatusRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Post $post): JsonResponse
    {
        $comments = Comment::where('post_id', $post->id)->get();

        return response()->json([
            'status' => true,
            'data' => CommentResource::collection($comments)
        ], 200);
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
    public function store(StoreCommentRequest $request, Post $post): JsonResponse
    {
        Gate::authorize('create', Comment::class);

        if ($post->status !== PostStatusType::PUBLISHED->value) {
            return response()->json([
                'status' => false,
                'message' => 'Post not published'
            ], 403);
        }

        $validatedData = $request->validated();
        $user = auth()->user();

        $comment = Comment::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'body' => $validatedData['body'],
            'status' => $user->hasRole('admin')
                ? CommentStatusType::APPROVED->value
                : CommentStatusType::PENDING->value,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Comment created successfully',
            'data' => new CommentResource($comment)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post, Comment $comment): JsonResponse
    {
        // if ($comment->post_id !== $post->id) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Comment not found'
        //     ], 404);
        // }

        if ($comment->status !== CommentStatusType::APPROVED->value) {
            return response()->json(['status' => false, 'message' => 'Comment not approved'], 403);
        }

        return response()->json([
            'status' => true,
            'data' => new CommentResource($comment)
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Post $post, Comment $comment): JsonResponse
    {
        Gate::authorize('update', $comment);

        if ($comment->post_id !== $post->id) {
            return response()->json([
                'status' => false,
                'message' => 'Comment not found'
            ], 404);
        }

        if ($post->status !== PostStatusType::PUBLISHED->value) {
            return response()->json([
                'status' => false,
                'message' => 'Post not published'
            ], 403);
        }

        $validatedData = $request->validated();

        $comment->update([
            'body' => $validatedData['body'],
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Comment updated successfully',
            'data' => new CommentResource($comment)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, Comment $comment): JsonResponse
    {
        Gate::authorize('delete', $comment);

        if ($comment->post_id !== $post->id) {
            return response()->json([
                'status' => false,
                'message' => 'Comment not found'
            ], 404);
        }

        if ($post->status !== PostStatusType::PUBLISHED->value) {
            return response()->json([
                'status' => false,
                'message' => 'Post not published'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'status' => true,
            'message' => 'Comment deleted successfully'
        ], 200);
    }

    public function updateStatus(UpdateCommentStatusRequest $request, Post $post, Comment $comment): JsonResponse
    {
        $validatedData = $request->validated();

        Gate::authorize('updateStatus', $comment);

        if ($comment->post_id !== $post->id) {
            return response()->json([
                'status' => false,
                'message' => 'Comment not found'
            ], 404);
        }

        $comment->update([
            'status' => $validatedData['status']
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Comment status updated successfully',
            'data' => new CommentResource($comment)
        ], 200);
    }
}
