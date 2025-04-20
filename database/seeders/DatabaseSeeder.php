<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
//        Post::factory(100)->create();

        $categories = Category::cursor();

        foreach ($categories as $category) {
            $category->slug = Str::slug($category->name);
            $category->save();
        }

        $posts = Post::cursor();

        foreach ($posts as $post) {
            $post->slug = Str::slug($post->name);
            $post->save();
        }
    }
}
