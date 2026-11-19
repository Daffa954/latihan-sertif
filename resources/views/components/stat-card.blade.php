
@props([
    'title',
    'value' => 0,
    'description' => '',
    'icon' => 'book-open',
])

<div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex items-center justify-between">
        <div class="text-sm font-medium text-slate-500">
            {{ $title }}
        </div>

        <div class="rounded-lg bg-blue-50 p-2 text-blue-600">
            {{ $icon }}
        </div>
    </div>

    <div class="mt-4">
        <p class="text-3xl font-bold text-slate-900">
            {{ $value }}
        </p>

        <p class="mt-1 text-xs text-slate-500">
            {{ $description }}
        </p>
    </div>
</div>
