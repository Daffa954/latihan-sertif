<header class="bg-white border-b border-gray-200">
    <nav class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        {{-- Logo --}}
        <a href="/" class="text-2xl font-bold text-blue-600">
            Library<span class="text-gray-900">App</span>
        </a>

        {{-- Navigation --}}
        <div class="hidden md:flex items-center gap-8 text-sm font-medium">
            <a href="/" class="text-gray-700 hover:text-blue-600">
                Home
            </a>

            <a href="#features" class="text-gray-700 hover:text-blue-600">
                Features
            </a>

            <a href="#about" class="text-gray-700 hover:text-blue-600">
                About
            </a>
        </div>

        {{-- Authentication --}}
        <div class="flex items-center gap-4">
            @if (session()->has('user_id'))
                <span class="text-sm font-medium text-gray-700">
                    Hai, {{ session('user_name') }}
                </span>

                <form action="{{ route('api.auth.logout') }}" method="POST">
                    @csrf

                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 text-sm font-semibold">
                        Logout
                    </button>
                </form>
            @else
                {{-- Tombol Login dan Register --}}

                <div x-data="{ authModal: null }" x-effect="document.body.style.overflow = authModal ? 'hidden' : ''"
                    class="flex gap-4">
                    <button type="button" @click="authModal = 'login'".
                        class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm font-semibold">
                        Login
                    </button>

                    <button type="button" @click="authModal = 'register'"
                        class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 text-sm font-semibold">
                        Register
                    </button>

                    <x-auth-modal type="login" />
                    <x-auth-modal type="register" />
                </div>
            @endif
        </div>

    </nav>
</header>
