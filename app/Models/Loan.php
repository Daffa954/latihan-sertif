<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'borrower_id',
        'approver_id',
        'status',
        'borrower_name',
        'approver_name',
        'borrowed_at',
        'returned_at',
        'due_date',
        'fine_amount',
    ];

    protected function casts(): array
    {
        return [
            'borrowed_at' => 'datetime',
            'returned_at' => 'datetime',
            'due_date' => 'datetime',
            'fine_amount' => 'decimal:2',
        ];
    }

    public function loanItems()
    {
        return $this->hasMany(LoanItem::class);
    }

    public function borrower()
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    /**
     * Aturan Bisnis: Cek apakah peminjaman sudah melewati batas waktu (due_date).
     */
    public function isOverdue(): bool
    {
        if (! $this->due_date) {
            return false;
        }

        $checkDate = $this->returned_at ?? now();

        return $checkDate->greaterThan($this->due_date);
    }

    /**
     * Aturan Bisnis: Hitung total hari keterlambatan.
     */
    public function getOverdueDaysAttribute(): int
    {
        if (! $this->isOverdue()) {
            return 0;
        }

        $checkDate = $this->returned_at ?? now();

        return (int) $this->due_date->diffInDays($checkDate);
    }

    /**
     * Aturan Bisnis: Hitung estimasi/nilai denda berdasarkan tarif per hari.
     */
    public function calculateFine(int $ratePerDay = 2000): float
    {
        return (float) ($this->overdue_days * $ratePerDay);
    }

    public static function getActiveLoansCount(?int $userId = null): int
    {
        $query = self::where('status', 'approved')->whereNull('returned_at');

        if ($userId !== null) {
            $query->where('borrower_id', $userId);
        }

        return $query->count();
    }

    public static function getPendingLoansCount(): int
    {
        return self::where('status', 'pending')->count();
    }
}
