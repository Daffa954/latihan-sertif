@extends('layouts.app')

@section('title', 'Home - LibraryApp')

@section('content')


    {{-- Dashboard Header --}}
    <section class="mb-8">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">
                    {{ session('user_role') === 'librarian' ? 'Librarian Dashboard' : 'Borrower Dashboard' }}
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Welcome back, {{ session('user_name') }}.
                    Here's your library overview.
                </p>
            </div>

            <span class="w-fit rounded-full bg-blue-50 px-3 py-1 text-sm font-medium capitalize text-blue-700">
                {{ session('user_role') }}
            </span>
        </div>
    </section>


    {{-- Statistics Section --}}
    <section>
        <h2 class="mb-4 text-lg font-semibold text-slate-800">
            Overview
        </h2>

        @if (session('user_role') === 'borrower')
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
                <x-stat-card title="Active Loans" :value="$statsBorrower['active_loans']" description="Currently borrowed books" icon="📚" />

                <x-stat-card title="Books to Return" :value="$statsBorrower['books_to_return']" description="Books awaiting return" icon="📅" />

                <x-stat-card title="Overdue Books" :value="$statsBorrower['overdue_books']" description="Past their return date" icon="⚠️" />

                <x-stat-card title="Total Borrowed" :value="$statsBorrower['total_borrowed']" description="Total borrowing history"
                    icon="📖" />
            </div>
        @elseif (session('user_role') === 'librarian')
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
                <x-stat-card title="Book Titles" :value="$statsLib['total_books']" description="Available book titles" icon="📚" />

                <x-stat-card title="Total Copies" :value="$statsLib['total_copies']" description="Total book inventory" icon="📦" />

                <x-stat-card title="Active Loans" :value="$statsLib['active_loans']" description="Currently active loans" icon="📋" />

                <x-stat-card title="Pending Requests" :value="$statsLib['pending_requests']" description="Awaiting approval" icon="⏳" />
            </div>
        @endif


    </section>
    <section class="mt-3" x-data="{
        loanModal: false,
        selectedBooks: JSON.parse(localStorage.getItem('selectedBooks') || '[]'),
    
        toggleBook(id) {
            if (this.selectedBooks.includes(id)) {
                this.selectedBooks = this.selectedBooks.filter(
                    bookId => bookId !== id
                );
            } else {
                this.selectedBooks.push(id);
            }
    
            localStorage.setItem(
                'selectedBooks',
                JSON.stringify(this.selectedBooks)
            );
        },
    
        isSelected(id) {
            return this.selectedBooks.includes(id);
        }
    }">
        <h2 class="mb-4 text-lg font-semibold text-slate-800">
            List Books
        </h2>

        <div x-show="selectedBooks.length > 0" x-cloak class="mb-4 text-sm text-slate-500">
            <p>
                Selected Books: <span x-text="selectedBooks.length"></span>
            </p>

            <button type="button" @click="loanModal = true; updateSelectedBooks()"
                class="mt-2 inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                Create Loan Request
            </button>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-6 py-4">
                                <input type="checkbox" aria-label="Select all books">
                            </th>
                            <th class="px-6 py-4 font-semibold">Title</th>
                            <th class="px-6 py-4 font-semibold">Author</th>
                            <th class="px-6 py-4 font-semibold">Category</th>
                            <th class="px-6 py-4 font-semibold">Total Copies</th>
                            <th class="px-6 py-4 font-semibold">Action</th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($totalBooksWithCopies as $book)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <input type="checkbox" value="{{ $book->id }}"
                                        :checked="isSelected({{ $book->id }})" @change="toggleBook({{ $book->id }})"
                                        aria-label="Select book {{ $book->title }}">
                                </td>

                                <td class="whitespace-nowrap px-6 py-4">
                                    {{ $book->title }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4">
                                    {{ $book->author }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4">
                                    {{ $book->category_name }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4">
                                    {{ $book->total_copies }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <button type="button" title="Show details" aria-label="Show book details"
                                        class="text-slate-500 hover:text-blue-600 transition-colors cursor-pointer">
                                        <x-icons.eye class="h-5 w-5 text-slate-500" />
                                    </button>
                                </td>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                                    No books found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $totalBooksWithCopies->links() }}
        </div>

        <x-loan-modal :action="route('loans.store')" />
    </section>


@endsection
<script>
    function updateSelectedBooks() {
        const selectedBooks = JSON.parse(
            localStorage.getItem('selectedBooks') || '[]'
        );

        const container = document.getElementById('selected-books-container');
        const list = document.getElementById('selected-books-list');

        // Bersihkan data sebelumnya
        container.innerHTML = '';
        list.innerHTML = '';

        selectedBooks.forEach(id => {
            // Hidden input untuk Laravel
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'book_ids[]';
            input.value = id;

            container.appendChild(input);

            // Tampilkan ID buku
            const item = document.createElement('li');
            item.textContent = `Book ID: ${id}`;
            item.className = 'rounded-lg bg-slate-50 px-3 py-2';

            list.appendChild(item);
        });
    }

    document.addEventListener('DOMContentLoaded', updateSelectedBooks);
</script>
