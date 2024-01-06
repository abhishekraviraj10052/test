<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for($i=0;$i<100;$i++){
            User::create([
                'firstname'=>$faker->firstName,
                'lastname'=>$faker->lastName,
                'email'=>$faker->email,
                'number'=>$faker->PhoneNumber,
                'role'=>0,
                'status'=>0,
            ]);

        }
     

        
    }
}
