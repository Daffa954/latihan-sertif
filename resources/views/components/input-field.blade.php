@props(['name', 'label', 'type' => 'text', 'placeholder' => ''])

<div class="mb-5">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-2">
        {{ $label }}
    </label>

    <div @if ($type === 'password') x-data="{ showPassword: false }" @endif class="relative">
        <input type="{{ $type }}"
            @if ($type === 'password') x-bind:type="showPassword ? 'text' : 'password'" @endif
            id="{{ $name }}" name="{{ $name }}" value="{{ $type === 'password' ? '' : old($name) }}"
            placeholder="{{ $placeholder }}" @class([
                'w-full rounded-lg bg-white px-4 py-2.5 text-sm text-gray-700 shadow-sm outline-none transition duration-200 placeholder:text-gray-400',
                'pr-11' => $type === 'password',
                'border border-red-500 focus:border-red-500 focus:ring-2 focus:ring-red-500/20' => $errors->has(
                    $name),
                'border border-gray-300 hover:border-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20' => !$errors->has(
                    $name),
            ]) {{ $attributes }}>

        @if ($type === 'password')
            <button type="button" @click="showPassword = !showPassword"
                class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600 transition"
                :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'">
                {{-- Eye Icon --}}
                <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 12s3.5-7 9.75-7 9.75 7 9.75 7-3.5 7-9.75 7-9.75-7-9.75-7Z" />
                    <circle cx="12" cy="12" r="3" />
                </svg>

                {{-- Eye Off Icon --}}
                <svg x-show="showPassword" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 3l18 18M10.58 10.58a2 2 0 0 0 2.83 2.83" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9.88 5.09A10.7 10.7 0 0 1 12 4.88c6.25 0 9.75 7.12 9.75 7.12a17.7 17.7 0 0 1-3.04 3.98M6.61 6.61C3.73 8.56 2.25 12 2.25 12s3.5 7.12 9.75 7.12c1.3 0 2.47-.3 3.5-.78" />
                </svg>
            </button>
        @endif
    </div>

    @error($name)
        <p class="mt-1.5 text-sm text-red-600">
            {{ $message }}
        </p>
    @enderror
</div>
