<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $request->validate([
            'body' => 'required|string',
        ]);

        $user = Auth::user();

        $comment = new Comment;
        $comment->body = $request->body;
        $comment->post()->associate($post);
        $comment->user()->associate($user);
        $comment->save();

        return response()->json([
            'result' => true,
            'message' => 'Comment added successfully', 
            'comment' => new CommentResource($comment)
        ]);
    }

    public function update(Request $request, $commentId)
    {
        $comment = Comment::find($commentId);

        if (!$comment) {
            return response()->json([
                'result' => false,
                'message' => 'Comment not found'
            ], 404);
        }

        if ($comment->post->user_id != auth()->user()->id) {
            return response()->json([
                'result' => false,
                'message' => 'You only can edit your own comments'
            ], 403);
        }

        $request->validate([
            'body' => 'required|string',
        ]);

        $comment->update([
            'body' => $request->body,
        ]);

        return response()->json([
            'result' => true,
            'message' => 'Comment updated successfully', 
            'comment' => $comment
        ]);
    }

    public function destroy($commentId)
    {
        $comment = Comment::find($commentId);

        if (!$comment) {
            return response()->json([
                'result' => false,
                'message' => 'Comment not found']
                , 404);
        }

        if ($comment->post->user_id != auth()->user()->id) {
            return response()->json([
                'result' => false,
                'message' => 'You only can edit your own comments'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'result' => true,
            'message' => 'Comment deleted successfully'
        ]);
    }
}
