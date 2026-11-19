@props(['action'])

<div
    x-show="loanModal"
    x-cloak
    x-transition.opacity.duration.200ms
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
>
    <div class="w-full max-w-lg rounded-xl bg-white p-6">

        <h2 class="mb-5 text-lg font-semibold">
            Create Loan Request
        </h2>

        <form action="{{ $action }}" method="POST">
            @csrf

            <div id="selected-books-container"></div>

            <div class="mb-5">
                <p class="mb-2 text-sm font-medium text-slate-700">
                    Selected Books
                </p>

                <ul
                    id="selected-books-list"
                    class="space-y-2 text-sm text-slate-600"
                ></ul>
            </div>

            <div class="mb-5">
                <label for="due_date" class="mb-2 block text-sm font-medium">
                    Due Date
                </label>

                <input
                    type="date"
                    name="due_date"
                    id="due_date"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2"
                    required
                >
            </div>

            <div class="flex justify-end gap-3">
                <button
                    type="button"
                    class="rounded-lg bg-gray-500 px-4 py-2 text-white"
                    @click="loanModal = false"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-white"
                >
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>