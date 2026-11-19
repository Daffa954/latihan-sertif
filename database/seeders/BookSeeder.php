<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $categories = Category::factory(3)->create();
        }

        foreach ($categories as $category) {
            Book::factory(5)->create([
                'category_id' => $category->id,
            ]);
        }
    }
}
