<?php

namespace App\Models;

use Database\Factories\BookCopyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookCopy extends Model
{
    /** @use HasFactory<BookCopyFactory> */
    use HasFactory;

    protected $fillable = [
        'book_id',
        'copy_number',
        'condition',
        'status',
        'created_by',
        'updated_by',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function loanItems()
    {
        return $this->hasMany(LoanItem::class);
    }

    public static function getTotalCopiesCount()
    {
        return self::count();
    }
}
