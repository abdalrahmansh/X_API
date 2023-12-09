<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'total_likes' => (int)$this->total_likes,
            'total_dislikes' => (int)$this->total_dislikes,
            'user' => new UserResource($this->user),
            'comments' => new CommentCollection($this->comments),
            'created_at' => $this->created_at->format('M j, Y H:i:s'),
            'updated_at' => $this->updated_at->format('M j, Y H:i:s'),
        ];
    }
}
