<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $a = 1;

        $categories = [
            ['name' => 'To Do', 'created_by' => $a],
            ['name' => 'InProgress', 'created_by' => $a],
            ['name' => 'Testing', 'created_by' => $a],
            ['name' => 'Done', 'created_by' => $a],
            ['name' => 'Pending', 'created_by' => $a],
        ];

        DB::table('categories')->insert($categories);
    }
}
