<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::firstOrCreate([
            'name' => 'Super Admin',
            'username' => 'her',
            'email' => 'her@her'
        ],[
            'password' => bcrypt('123'),
        ]);

        $superAdmin->assignRole('super-admin');
    }
}
