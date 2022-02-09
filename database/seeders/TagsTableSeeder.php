<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Faker\Factory;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        for ($i=0; $i < 5; $i++) {
            Tag::create([
                'name' => $faker->name(),
            ]);
        }
    }
}
