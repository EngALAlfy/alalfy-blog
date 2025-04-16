<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;

class PostsController extends Controller
{
    public function all(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return PostResource::collection(Post::with('category', 'tags')->withCount("comments", "tags")->get());
    }

    public function latest(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return PostResource::collection(Post::with('category', 'tags')->withCount("comments", "tags")->get());
    }

    public function hero(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return PostResource::collection(Post::with('category', 'tags')->withCount("comments", "tags")->get());
    }

    public function featured(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return PostResource::collection(Post::with('category', 'tags')->withCount("comments", "tags")->get());
    }

    public function show(Post $post): PostResource
    {
        return new PostResource(
            $post->load('category', 'tags')->loadCount("comments", "tags")
        );
    }

}
