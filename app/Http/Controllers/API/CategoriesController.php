<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoriesController extends Controller
{
    public function all(): AnonymousResourceCollection
    {
        return CategoryResource::collection(Category::with('posts', 'tags')->withCount("posts", "tags")->get());
    }

    public function home(): AnonymousResourceCollection
    {
        return CategoryResource::collection(Category::with('posts', 'tags')->withCount("posts", "tags")->get());
    }

    public function header(): AnonymousResourceCollection
    {
        return CategoryResource::collection(Category::with('posts', 'tags')->withCount("posts", "tags")->get());
    }

    public function footer(): AnonymousResourceCollection
    {
        return CategoryResource::collection(Category::with('posts', 'tags')->withCount("posts", "tags")->get());
    }

    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category->load('posts', 'tags')->loadCount("posts", "tags"));
    }
}
