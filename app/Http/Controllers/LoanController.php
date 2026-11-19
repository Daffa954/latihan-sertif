<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\User;
use App\Services\LoanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function __construct(
        protected LoanService $loanService
    ) {}

    /**
     * Menerima request peminjaman dan mendelegasikan ke LoanService.
     */
    public function createLoan(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'book_copy_ids' => 'required|array|min:1',
            'book_copy_ids.*' => 'required|integer|exists:book_copies,id',
        ]);

        $userId = auth()->id() ?? session('user_id');
        $user = User::find($userId);

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $loan = $this->loanService->createLoan($user, $validated['book_copy_ids']);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Pengajuan peminjaman berhasil dibuat.',
                'data' => $loan,
            ], 201);
        }

        return redirect()->back()->with('success', 'Pengajuan peminjaman berhasil dibuat.');
    }

    /**
     * Pustakawan menyetujui peminjaman.
     */
    public function approve(Request $request, Loan $loan): JsonResponse|RedirectResponse
    {
        $librarianId = auth()->id() ?? session('user_id');
        $librarian = User::find($librarianId);

        if (! $librarian || $librarian->role !== 'librarian') {
            return response()->json(['message' => 'Hanya pustakawan yang dapat menyetujui peminjaman.'], 403);
        }

        $loan = $this->loanService->approveLoan($loan, $librarian);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Peminjaman berhasil disetujui.',
                'data' => $loan,
            ]);
        }

        return redirect()->back()->with('success', 'Peminjaman berhasil disetujui.');
    }

    /**
     * Pengembalian buku dan kalkulasi denda.
     */
    public function returnBook(Request $request, Loan $loan): JsonResponse|RedirectResponse
    {
        $loan = $this->loanService->returnLoan($loan);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Buku berhasil dikembalikan.',
                'data' => $loan,
            ]);
        }

        return redirect()->back()->with('success', 'Buku berhasil dikembalikan.');
    }
}
