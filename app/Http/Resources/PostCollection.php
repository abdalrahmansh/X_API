<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PostCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function($post){
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'body' => $post->body,
                    'user' => new UserResource($post->user),
                    'comments' => new CommentCollection($post->comments),
                    'created_at' => $post->created_at->format('M j, Y H:i:s'),
                    'updated_at' => $post->updated_at->format('M j, Y H:i:s'),
                ];
            }),
        ];
    }
}
