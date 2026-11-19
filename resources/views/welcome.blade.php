@extends('layouts.app')

@section('title', 'Home - LibraryApp')

@section('content')

    {{-- Hero Section --}}
    <section class="max-w-7xl mx-auto px-6 py-24">
        <div class="max-w-3xl mx-auto text-center">

            <span class="inline-block px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                Welcome to LibraryApp
            </span>

            <h1 class="mt-6 text-4xl md:text-6xl font-bold tracking-tight text-gray-900">
                Discover Knowledge,
                <span class="text-blue-600">One Book at a Time.</span>
            </h1>

            <p class="mt-6 text-lg text-gray-600 leading-relaxed">
                Explore our book collection, manage your borrowing activities,
                and enjoy a simpler library experience.
            </p>

            <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
                @auth
                    <a href="#features" class="px-6 py-3 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700">
                        Explore Features
                    </a>
                @else
                    <a class="px-6 py-3 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700">
                        Get Started
                    </a>

                    <a class="px-6 py-3 rounded-lg border border-gray-300 text-gray-700 font-semibold hover:bg-gray-100">
                        Create Account
                    </a>
                @endauth
            </div>

        </div>
    </section>

    {{-- Features Section --}}
    <section id="features" class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-6">

            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">
                    What You Can Do
                </h2>

                <p class="mt-3 text-gray-600">
                    Everything you need for a better library experience.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <div class="p-8 rounded-2xl border border-gray-200">
                    <div class="text-3xl mb-4">📚</div>

                    <h3 class="text-xl font-semibold">
                        Explore Books
                    </h3>

                    <p class="mt-3 text-gray-600">
                        Discover and explore books available in the library.
                    </p>
                </div>

                <div class="p-8 rounded-2xl border border-gray-200">
                    <div class="text-3xl mb-4">📖</div>

                    <h3 class="text-xl font-semibold">
                        Borrow Books
                    </h3>

                    <p class="mt-3 text-gray-600">
                        Submit borrowing requests and keep track of your loans.
                    </p>
                </div>

                <div class="p-8 rounded-2xl border border-gray-200">
                    <div class="text-3xl mb-4">📋</div>

                    <h3 class="text-xl font-semibold">
                        Manage Loans
                    </h3>

                    <p class="mt-3 text-gray-600">
                        Monitor borrowing status and return information.
                    </p>
                </div>

            </div>
        </div>
    </section>

    {{-- About Section --}}
    <section id="about" class="max-w-7xl mx-auto px-6 py-20 text-center">
        <h2 class="text-3xl font-bold text-gray-900">
            About LibraryApp
        </h2>

        <p class="mt-5 max-w-2xl mx-auto text-gray-600 leading-relaxed">
            LibraryApp is designed to simplify library operations
            and provide a convenient experience for borrowers and librarians.
        </p>
    </section>

@endsection
