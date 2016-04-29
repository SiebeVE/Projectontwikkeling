<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
            'name' => "Siebe Vanden Eynden",
            'email' => "siebe@siebeve.be",
            'password' => '$2y$10$khUBFjN1FeLuAOtScKm3qukzik669cwbp6Otk4AjoSbdyMVWvg4MG',
            'firstname' => "Siebe",
            'lastname' => 'Vanden Eynden',
            'address' => '',
            'house_number' => 20,
            'city' => '',
            'postal_code' => 0,
            'telephone' => '',
            'is_admin' => false,
            'remember_token' => '7qp3kfJ1yQFEMVNqQMF7ZAh634KoQBIBzpQj8jF2vsxSB0y9cHaDlUh1FpXH',
        ]);

        /*DB::table('projects')->insert([
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

        ]);*/

        //DB::table('phases')->insert([
        //   'project_id' => 1,
        //    'name' => 'fase 1.4',
        //    'start' => date("2016-06-15 H:i:s"),
        //    'end' => date("2016-06-18 H:i:s"),
        //    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        //]);
    }
}
