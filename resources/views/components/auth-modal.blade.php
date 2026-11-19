@props(['type' => 'login'])

<div x-show="authModal === '{{ $type }}'" x-cloak x-transition.opacity.duration.200ms
    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-sm p-4"
    @click.self="authModal = null">
    <div x-transition.scale.duration.200ms
        class="relative w-full max-w-md max-h-[95vh] overflow-y-auto rounded-2xl bg-white shadow-2xl ring-1 ring-black/5">
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-slate-900">
                    {{ $type === 'login' ? 'Welcome Back' : 'Create an Account' }}
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    {{ $type === 'login' ? 'Sign in to continue to your account.' : 'Register to get started with your account.' }}
                </p>
            </div>

            <button type="button" @click="authModal = null" aria-label="Close modal"
                class="flex h-9 w-9 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="px-6 py-6">
            @if ($type === 'login')
                <form action="{{ route('api.auth.login') }}" method="POST" class="space-y-5">
                    @csrf
                    <x-input-field name="email" label="Email" type="email" placeholder="Enter your email"
                        required />
                    <x-input-field name="password" label="Password" type="password" placeholder="Enter your password"
                        required />
                    <button type="submit"
                        class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        Login
                    </button>
                </form>
            @else
                <form action="{{ route('api.auth.register') }}" method="POST" class="space-y-5">
                    @csrf
                    <x-input-field name="email" label="Email" type="email" placeholder="Enter your email"
                        required />
                    <x-input-field name="name" label="Name" type="text" placeholder="Enter your name"
                        required />
                    <x-input-field name="username" label="Username" type="text" placeholder="Enter your username"
                        required />
                    <x-select-field name="grade" label="Grade" :options="[
                        'bachelor' => 'Bachelor',
                        'magister' => 'Magister',
                        'lecturer' => 'Lecturer',
                        'staff' => 'Staff',
                    ]" />
                    <x-input-field name="password" label="Password" type="password" placeholder="Enter your password"
                        required />
                    <x-input-field name="password_confirmation" label="Confirm Password" type="password"
                        placeholder="Confirm your password" required />
                    <button type="submit"
                        class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        Register
                    </button>
                </form>
            @endif
        </div>

        {{-- Footer --}}

    </div>
</div>
