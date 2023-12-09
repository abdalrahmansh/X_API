<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function($comment){
                return [
                    'id' => $comment->id,
                    'body' => $comment->body,
                    'user' => new UserResource($comment->post->user),
                    'created_at' => $comment->created_at->format('M j, Y H:i:s'),
                    'updated_at' => $comment->updated_at->format('M j, Y H:i:s'),
                ];
            })
        ];
    }
}
