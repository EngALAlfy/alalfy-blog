<?php

namespace App\Http\Resources;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Post */
class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'status' => $this->status,
            'status_at' => $this->status_at,
            'comments_count' => $this->whenCounted("comments"),
            'tags_count' => $this->whenCounted("tags"),
            'tags' => TagResource::collection($this->whenLoaded("tags")),
            'author' => new UserResource($this->author),
            'category' => new CategoryResource($this->category),
            'banner' => $this->getFirstMediaUrl('banner' , 'banner'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
