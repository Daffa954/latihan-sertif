<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookCopy;
use Illuminate\Database\Seeder;

class BookCopySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = Book::all();

        if ($books->isEmpty()) {
            $books = Book::factory(5)->create();
        }

        foreach ($books as $book) {
            BookCopy::factory(rand(2, 5))->create([
                'book_id' => $book->id,
            ]);
        }
    }
}
