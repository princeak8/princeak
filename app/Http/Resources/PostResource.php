<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\TagResource;
use App\Http\Resources\FileResource;
use App\Http\Resources\CommentResource;

class PostResource extends JsonResource
{
    public static $wrap = null;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resource = [
            "id" => $this->id,
            "title" => $this->title,
            "slug" => $this->slug,
            // "category" => new CategoryResource($this->category),
            "status" => $this->status,
            "coverPhoto" => new FileResource($this->coverPhoto),
            "content" => $this->content,
            "preview" => $this->preview,
            "tags" => TagResource::collection($this->tags),
            "comments" => CommentResource::collection($this->comments)
        ];

        $resource["category"] = ($this->category) ? new CategoryResource($this->category) : null;

        return $resource;
    }
}
