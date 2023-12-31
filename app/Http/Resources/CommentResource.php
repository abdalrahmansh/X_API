<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'body' => $this->body,
            'user' => new UserResource($this->post->user),
            'created_at' => $this->created_at->format('M j, Y H:i:s'),
            'updated_at' => $this->updated_at->format('M j, Y H:i:s'),
        ];
    }
}
