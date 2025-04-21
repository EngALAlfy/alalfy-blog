<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostsController extends Controller
{
    /**
     * Get the base query builder with common relationships and counts
     *
     * @return Builder
     */
    protected function getBaseQuery(): Builder
    {
        return Post::with('category', 'tags', 'author')
                  ->withCount('comments', 'tags');
    }

    /**
     * Get all posts
     *
     * @return AnonymousResourceCollection
     */
    public function all(): AnonymousResourceCollection
    {
        return PostResource::collection($this->getBaseQuery()->get());
    }

    /**
     * Get latest posts
     *
     * @return AnonymousResourceCollection
     */
    public function latest(): AnonymousResourceCollection
    {
        return PostResource::collection(
            $this->getBaseQuery()
                 ->latest('created_at') // Added ordering by creation date
                 ->limit(9)
                 ->get()
        );
    }

    /**
     * Get hero posts
     *
     * @return AnonymousResourceCollection
     */
    public function hero(): AnonymousResourceCollection
    {
        return PostResource::collection(
            $this->getBaseQuery()
                 ->limit(5)
                 ->get()
        );
    }

    /**
     * Get featured posts
     *
     * @return AnonymousResourceCollection
     */
    public function featured(): AnonymousResourceCollection
    {
        // Modified to differentiate from latest() method
        return PostResource::collection(
            $this->getBaseQuery()
                 ->where('status', 'published') // Assuming there's a status field
                 ->limit(9)
                 ->get()
        );
    }

    /**
     * Show a specific post
     *
     * @param Post $post
     * @return PostResource
     */
    public function show(Post $post): PostResource
    {
        return new PostResource(
            $post->load('category', 'tags', 'author')
                 ->loadCount('comments', 'tags')
        );
    }
}
