<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $name = 'User ' . ($i + 1);
            $users[] = [
                'name' => $name,
                'username' => $name,
                'email' => 'user' . ($i + 1) . '@test.com',
                'password' => Hash::make('123'),
                'created_at' => now(),
            ];
        }

        DB::table('users')->insert($users);
    }
}
