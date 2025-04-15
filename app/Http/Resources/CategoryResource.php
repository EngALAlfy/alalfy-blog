<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Category */
class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'posts_count' => $this->whenCounted("posts"),
            'tags_count' => $this->whenCounted("tags"),
            'posts' => PostResource::collection($this->whenLoaded("posts")),
            'tags' => TagResource::collection($this->whenLoaded("tags")),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'banner' => $this->getFirstMediaUrl('banner' , 'banner'),
        ];
    }
}
