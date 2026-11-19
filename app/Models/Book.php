<?php

namespace App\Models;

use Database\Factories\BookFactory;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /** @use HasFactory<BookFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'description',
        'category_id',
        'cover_image',
        'created_by',
        'updated_by',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function copies()
    {
        return $this->hasMany(BookCopy::class);
    }

    public static function getTotalBooksCount()
    {
        return self::count();
    }

    public static function getBooksWithCopyTotals(int $perPage = 10)
{
    $bookQuery = DB::table('books as b')
        ->leftJoin('categories as c', 'b.category_id', '=', 'c.id')
        ->select(
            'b.id',
            'b.title',
            'b.author',
            'b.description',
            'c.name as category_name',
            'b.cover_image'
        )
        ->selectSub(function ($query) {
            $query->from('book_copies')
                ->selectRaw('COUNT(*)')
                ->whereColumn('book_id', 'b.id');
        }, 'total_copies');

    return DB::query()
        ->fromSub($bookQuery, 'books')
        ->paginate($perPage);
}
}
