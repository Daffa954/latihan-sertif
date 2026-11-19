<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LoanPolicy extends Model
{
    protected $fillable = [
        'grade',
        'max_borrowed_books',
        'max_borrow_duration',
    ];

    /**
     * Konfigurasi aturan default peminjaman berdasarkan jenjang (grade).
     */
    public const DEFAULT_POLICIES = [
        'bachelor' => [
            'max_borrowed_books' => 3,
            'max_borrow_duration' => 7,  // 7 hari (1 minggu)
        ],
        'magister' => [
            'max_borrowed_books' => 5,
            'max_borrow_duration' => 14, // 14 hari (2 minggu)
        ],
        'lecturer' => [
            'max_borrowed_books' => 10,
            'max_borrow_duration' => 30, // 30 hari (1 bulan)
        ],
        'staff' => [
            'max_borrowed_books' => 4,
            'max_borrow_duration' => 14, // 14 hari (2 minggu)
        ],
        'head' => [
            'max_borrowed_books' => 10,
            'max_borrow_duration' => 30, // 30 hari (1 bulan)
        ],
    ];

    /**
     * Ambil kebijakan peminjaman untuk grade tertentu.
     * Mengambil dari database, atau fallback ke default.
     */
    public static function forGrade(string $grade): self
    {
        return static::where('grade', $grade)->first() ?? static::make(
            array_merge(
                ['grade' => $grade],
                self::DEFAULT_POLICIES[$grade] ?? self::DEFAULT_POLICIES['bachelor']
            )
        );
    }

    /**
     * Hitung tanggal jatuh tempo (due_date) dari tanggal mulai.
     */
    public function calculateDueDate(?Carbon $startDate = null): Carbon
    {
        $baseDate = $startDate ? clone $startDate : now();

        return $baseDate->addDays($this->max_borrow_duration);
    }
}
