<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        $categories = collect(Category::all()->modelKeys());
        $users = collect(User::where('id','>',2)->get()->modelKeys());

        for ($i=0; $i < 100; $i++) {
            $post = Post::create([
                'title'         => $faker->sentence(mt_rand(3,6),true),
                'description'   => $faker->paragraph,
                'status'        => random_int(0,1),
                'comment_able'  => random_int(0,1),
                'user_id'       => $users->random(),
                'category_id'   => $categories->random(),
            ]);
        }
    }
}
