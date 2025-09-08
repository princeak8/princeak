<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\CommentReplyResource;
use App\Http\Resources\UserResource;

class CommentResource extends JsonResource
{
    public static $wrap = null;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "message" => $this->message,
            "replies" => CommentReplyResource::collection($this->replies),
            "user" => new UserResource($this->user),
            "createdAt" => $this->created_at
        ];
    }
}
