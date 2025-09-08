<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public static $wrap = null;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array | null
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "canDelete" => $this->canDelete(),
            "postsCount" => $this->posts->count()
        ];
    }

    private function canDelete()
    {
        return ($this->posts->count() == 0);
    }
}
