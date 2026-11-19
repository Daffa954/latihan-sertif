
@props([
    'columns' => [],
    'rows' => [],
    'emptyMessage' => 'No data available.',
])

<div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                    @foreach ($columns as $column)
                        <th class="whitespace-nowrap px-6 py-4 font-semibold">
                            @if (($column['type'] ?? null) === 'checkbox')
                                <input type="checkbox" aria-label="Select all books">
                            @else
                                {{ $column['label'] }}
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse ($rows as $row)
                    <tr class="hover:bg-gray-50">
                        @foreach ($columns as $column)
                            <td class="whitespace-nowrap px-6 py-4 text-slate-700">
                                @if (($column['type'] ?? null) === 'checkbox')
                                    <input
                                        type="checkbox"
                                        value="{{ $row->id }}"
                                        aria-label="Select book {{ $row->title }}"
                                        :checked="isSelected({{ $row->id }})"
                                        @click="toggleBook({{ $row->id }})"
                                    >
                                @else
                                    {{ data_get($row, $column['key']) ?? '-' }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td
                            colspan="{{ count($columns) }}"
                            class="px-6 py-10 text-center text-gray-400"
                        >
                            {{ $emptyMessage }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
