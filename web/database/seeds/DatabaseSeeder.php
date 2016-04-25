<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

        public function run()
    {
        DB::table('users')->insert([
            'name' => str_random(10),
            'email' => str_random(10).'@gmail.com',
            'password' => bcrypt('secret'),
            'firstname' => str_random(10),
            'lastname' => str_random(15),
            'address' => str_random(20),
            'house_number' => 20,
            'city' => 'Antwerpen',
            'postal_code' => 2000,
            'telephone' => '0488779930',
            'is_admin' => false,

        ]);

        DB::table('projects')->insert([
            'user_id' => 1,
            'name' => 'project 1',
            'description' => 'test',
            'created_by' => str_random(10),

        ]);

        DB::table('projects')->insert([
            'user_id' => 1,
            'name' => 'project 2',
            'description' => 'test',
            'created_by' => str_random(10),

        ]);
    }
}
