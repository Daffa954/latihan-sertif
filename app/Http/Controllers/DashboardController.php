<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Loan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {   
        $totalBooks = Book::getTotalBooksCount();
        // Get id from session
        $borrowerId = session('user_id');
        $totalBooksWithCopies = Book::getBooksWithCopyTotals();

        $statsBorrower = [
            'active_loans' => Loan::getActiveLoansCount($borrowerId),
            'books_to_return' => 2,
            'overdue_books' => 1,
            'total_borrowed' => 12,
        ];

        // Librarian
        $statsLib = [
            'total_books' => Book::getTotalBooksCount(),
            'total_copies' => BookCopy::getTotalCopiesCount(),
            'active_loans' => Loan::getActiveLoansCount(),
            'pending_requests' => Loan::getPendingLoansCount(),
        ];

        return view('dashboard', compact('statsBorrower', 'statsLib', 'totalBooksWithCopies', ));

    }
}
