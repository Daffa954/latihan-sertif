<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanItem extends Model
{
    protected $fillable = [
        'loan_id',
        'book_copy_id',
    ];

    public function bookCopy()
    {
        return $this->belongsTo(BookCopy::class);
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
