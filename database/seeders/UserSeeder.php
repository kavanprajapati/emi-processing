<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Developer',
                'email' => 'developer@test.com',
                'password' => bcrypt('Test@Password123#')
            ]
        );
    }
}
