<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::create(['name'=>'admin','display_name'=>'Administrator','description'=>'System Administrator','allowed_route'=>'admin']);
        $editorRole = Role::create(['name'=>'editor','display_name'=>'Supervisor','description'=>'System Supervisor','allowed_route'=>'admin']);
        $userRole = Role::create(['name'=>'user','display_name'=>'User','description'=>'Normal User','allowed_route'=>null]);


        $admin = User::create([
            'name'              =>'Admin',
            'username'          =>'admin',
            'email'             =>'admin@blog-cms.com',
            'mobile'            =>'0123456789',
            'email_verified_at' => Carbon::now(),
            'password'          =>bcrypt('123123123'),
            'status'            =>1,
        ]);
        $admin->attachRole($adminRole);

        $editor = User::create([
            'name'              =>'Editor',
            'username'          =>'editor',
            'email'             =>'editor@blog-cms.com',
            'mobile'            =>'0123456781',
            'email_verified_at' => Carbon::now(),
            'password'          => bcrypt('123123123'),
            'status'            =>1,
        ]);

        $editor->attachRole($editorRole);

        // user 1
        $user = User::create([
            'name'              =>'Ahmed Shorafa',
            'username'          =>'ahmed',
            'email'             =>'ahmed@blog-cms.com',
            'mobile'            =>'0123456782',
            'email_verified_at' => Carbon::now(),
            'password'          => bcrypt('123123123'),
            'status'            =>1,
        ]);
        $user->attachRole($userRole);

        // user 2
        $user2 = User::create([
            'name'              =>'Mahmoud Shorafa',
            'username'          =>'mahmoud',
            'email'             =>'mahmoud@blog-cms.com',
            'mobile'            =>'0123456783',
            'email_verified_at' => Carbon::now(),
            'password'          => bcrypt('123123123'),
            'status'            =>1,
        ]);
        $user2->attachRole($userRole);

        // user 3
        $user3 = User::create([
            'name'              =>'Ali Shorafa',
            'username'          =>'ali',
            'email'             =>'ali@blog-cms.com',
            'mobile'            =>'0123456784',
            'email_verified_at' => Carbon::now(),
            'password'          => bcrypt('123123123'),
            'status'            =>1,
        ]);
        $user3->attachRole($userRole);


        $faker = Factory::create();
        // many users
        for ($i=0; $i < 10 ; $i++) {
            $user = User::create([
                'name'              => $faker->name,
                'username'          => $faker->userName,
                'email'             => $faker->email,
                'mobile'            =>'0123' . random_int(1000000,99999999),
                'email_verified_at' => Carbon::now(),
                'password'          => bcrypt('123123123'),
                'status'            =>1,
            ]);
            $user->attachRole($userRole);
        }
    }
}
