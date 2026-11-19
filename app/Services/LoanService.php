<?php

namespace App\Services;

use App\Models\BookCopy;
use App\Models\Loan;
use App\Models\LoanItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LoanService
{
    /**
     * Alur Sistem: Membuat pengajuan peminjaman buku baru.
     * Menggabungkan aturan bisnis User, LoanPolicy, dan pembaruan multi-tabel.
     *
     * @param  User  $borrower
     * @param  array<int>  $bookCopyIds
     * @return Loan
     *
     * @throws ValidationException
     */
    public function createLoan(User $borrower, array $bookCopyIds): Loan
    {
        $bookCopyIds = array_unique($bookCopyIds);
        $totalItems = count($bookCopyIds);

        // 1. Aturan Bisnis: Validasi kuota pinjam sesuai grade
        if (! $borrower->canBorrowBooks($totalItems)) {
            $quota = $borrower->remainingBorrowQuota();
            throw ValidationException::withMessages([
                'book_copy_ids' => "Peminjaman melebihi batas kuota. Sisa kuota Anda: {$quota} buku.",
            ]);
        }

        return DB::transaction(function () use ($borrower, $bookCopyIds) {
            // 2. Lock & validasi ketersediaan salinan buku
            $copies = BookCopy::whereIn('id', $bookCopyIds)
                ->lockForUpdate()
                ->get();

            if ($copies->count() !== count($bookCopyIds)) {
                throw ValidationException::withMessages([
                    'book_copy_ids' => 'Satu atau lebih salinan buku tidak ditemukan.',
                ]);
            }

            $unavailableCopies = $copies->where('status', '!=', 'available');
            if ($unavailableCopies->isNotEmpty()) {
                $numbers = $unavailableCopies->pluck('copy_number')->implode(', ');
                throw ValidationException::withMessages([
                    'book_copy_ids' => "Salinan buku berikut sedang tidak tersedia: {$numbers}.",
                ]);
            }

            // 3. Aturan Bisnis: Tentukan batas pengembalian (due_date) dari policy grade user
            $dueDate = $borrower->loanPolicy()->calculateDueDate(now());

            // 4. Insert header Loan
            $loan = Loan::create([
                'borrower_id' => $borrower->id,
                'borrower_name' => $borrower->name,
                'approver_id' => null,
                'approver_name' => null,
                'status' => 'pending',
                'borrowed_at' => now(),
                'due_date' => $dueDate,
                'fine_amount' => 0,
            ]);

            // 5. Insert detail LoanItem & update status BookCopy
            foreach ($copies as $copy) {
                LoanItem::create([
                    'loan_id' => $loan->id,
                    'book_copy_id' => $copy->id,
                ]);

                $copy->update([
                    'status' => 'borrowed',
                    'updated_by' => $borrower->username,
                ]);
            }

            return $loan->load(['loanItems.bookCopy.book']);
        });
    }

    /**
     * Alur Sistem: Persetujuan peminjaman oleh pustakawan.
     */
    public function approveLoan(Loan $loan, User $approver): Loan
    {
        if ($loan->status !== 'pending') {
            throw ValidationException::withMessages([
                'loan' => 'Hanya peminjaman berstatus pending yang dapat disetujui.',
            ]);
        }

        $loan->update([
            'status' => 'approved',
            'approver_id' => $approver->id,
            'approver_name' => $approver->name,
        ]);

        return $loan;
    }

    /**
     * Alur Sistem: Pengembalian buku dan kalkulasi denda otomatis.
     */
    public function returnLoan(Loan $loan): Loan
    {
        if ($loan->returned_at !== null) {
            throw ValidationException::withMessages([
                'loan' => 'Peminjaman ini sudah dikembalikan sebelumnya.',
            ]);
        }

        return DB::transaction(function () use ($loan) {
            $fine = $loan->calculateFine(ratePerDay: 2000);

            $loan->update([
                'returned_at' => now(),
                'fine_amount' => $fine,
            ]);

            // Kembalikan semua salinan buku menjadi available
            $copyIds = $loan->loanItems()->pluck('book_copy_id');
            BookCopy::whereIn('id', $copyIds)->update([
                'status' => 'available',
            ]);

            return $loan;
        });
    }
}
