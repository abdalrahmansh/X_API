<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\Post;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::orderby('created_at', 'desc')->get();
        return new PostCollection($posts);
    }

    public function show($postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return response()->json([
                'result' => false,
                'message' => 'Post not found'
            ], 404);
        }

        return new PostResource($post);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'string',
            'body' => 'required|string',
        ]);

        $post = Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => Auth::user()->id,
        ]);
        return response()->json([
            'result' => true,
            'message' => 'Post added successfully', 
            'post' => new PostResource($post)
        ]);
    }

    public function update(Request $request, $postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return response()->json([
                'result' => false,
                'message' => 'Post not found'
            ], 404);
        }

        if ($post->user_id != auth()->user()->id) {
            return response()->json([
                'result' => false,
                'message' => 'You only can edit your own posts'
            ], 403);
        }

        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        $post->update([
            'title' => $request->title,
            'body' => $request->body,
        ]);

        return new PostResource($post);
    }

    public function destroy($postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return response()->json([
                'result' => false,
                'message' => 'Post not found'
            ], 404);
        }

        if ($post->user_id != auth()->user()->id) {
            return response()->json([
                'result' => false,
                'message' => 'You only can edit your own posts'
            ], 403);
        }

        $post->delete();

        return response()->json([
            'result' => true,
            'message' => 'Post deleted successfully'
        ]);
    }

    public function like($postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return response()->json([
                'result' => false,
                'message' => 'Post not found'
            ], 404);
        }

        $post->total_likes++;
        $post->save();

        return response()->json([
            'result' => true,
            'message' => 'Post liked successfully'
        ]);
    }

    public function dislike($postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return response()->json([
                'result' => false,
                'message' => 'Post not found'
            ], 404);
        }

        $post->total_dislikes++;
        $post->save();

        return response()->json([
            'result' => true,
            'message' => 'Post disliked successfully'
        ]);
    }
}
