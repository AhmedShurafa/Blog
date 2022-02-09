<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call(RolesTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(TagsTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(PostsTableSeeder::class);
        $this->call(PostsTagsTableSeeder::class);
        $this->call(PagesTableSeeder::class);
        $this->call(CommentsTableSeeder::class);
        $this->call(SettingTableSeeder::class);
    }
}
