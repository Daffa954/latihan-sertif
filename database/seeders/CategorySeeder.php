<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Computer Science', 'description' => 'Programming, algorithms, and systems.'],
            ['name' => 'Fiction', 'description' => 'Novels, short stories, and literature.'],
            ['name' => 'Science', 'description' => 'Physics, biology, and chemistry.'],
            ['name' => 'History', 'description' => 'World history and historical analysis.'],
            ['name' => 'Philosophy', 'description' => 'Logic, ethics, and philosophy.'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['name' => $cat['name']],
                [
                    'description' => $cat['description'],
                    'created_by' => 'system',
                ]
            );
        }
    }
}
