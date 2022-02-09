<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Facade;

class PostsTagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = Post::all();

        foreach ($posts as $post) {
            $tags = Tag::inRandomOrder()->take(3)->pluck('id')->toArray();

            $post->tags()->sync($tags);
        }
    }
}
