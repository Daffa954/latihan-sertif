
@props([
    'name',
    'label',
    'options' => [],
    'placeholder' => 'Pilih pilihan',
])

<div class="mb-5">
    <label
        for="{{ $name }}"
        class="block text-sm font-medium text-gray-700 mb-2"
    >
        {{ $label }}
    </label>

    <div class="relative">
        <select
            id="{{ $name }}"
            name="{{ $name }}"
            {{ $attributes->merge([
                'class' => 'w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-700 shadow-sm outline-none transition duration-200 cursor-pointer hover:border-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20'
            ]) }}
        >
            <option value="" disabled selected>
                {{ $placeholder }}
            </option>

            @foreach ($options as $value => $text)
                <option value="{{ $value }}">
                    {{ $text }}
                </option>
            @endforeach
        </select>

        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
            <svg
                class="h-4 w-4"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="m19 9-7 7-7-7"
                />
            </svg>
        </div>
    </div>
</div>
