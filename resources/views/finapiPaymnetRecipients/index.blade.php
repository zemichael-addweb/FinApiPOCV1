<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('FinAPI Payment Recipient') }}
        </h2>
    </x-slot>

    <x-slot name="slot">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="max-w-2xl mx-auto py-12 text-slate-900 dark:text-slate-100">
                        @if(!$recipient)
                            <div class="text-center">
                                <h1 class="text-3xl font-bold mb-6">No Recipient Found</h1>
                                <a href="{{ route('finapi-payment-recipient.create') }}"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                    Add Recipient
                                </a>
                            </div>
                        @else
                        <h1 class="text-3xl font-bold mb-6">Recipients</h1>
                        <!-- Button to add a new recipient -->
                        <div class="mb-4">
                            <a href="{{ route('finapi-payment-recipient.edit', $recipient->id) }}"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Edit Recipient
                            </a>
                        </div>
                        <hr>
                        <!-- List of recipient infomation (NO TABLE) -->
                        <div class="mt-4">
                            <div class="grid grid-cols-2 gap-4 border-b border-slate-200 pb-4 mb-4 dark:border-slate-700">
                                <span class="font-bold">Name:</span>
                                <span>{{ $recipient->name }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-4 border-b border-slate-200 pb-4 mb-4 dark:border-slate-700">
                                <span class="font-bold">IBAN:</span>
                                <span>{{ $recipient->iban }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-4 border-b border-slate-200 pb-4 mb-4 dark:border-slate-700">
                                <span class="font-bold">BIC:</span>
                                <span>{{ $recipient->bic }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-4 border-b border-slate-200 pb-4 mb-4 dark:border-slate-700">
                                <span class="font-bold">Bank Name:</span>
                                <span>{{ $recipient->bank_name }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-4 border-b border-slate-200 pb-4 mb-4 dark:border-slate-700">
                                <span class="font-bold">City:</span>
                                <span>{{ $recipient->city }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-4 border-b border-slate-200 pb-4 mb-4 dark:border-slate-700">
                                <span class="font-bold">Country:</span>
                                <span>{{ $recipient->country }}</span>
                            </div>

                            <!-- Action to edit only -->
                            <div class="mb-4">
                                <a href="{{ route('finapi-payment-recipient.edit', $recipient->id) }}"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                    Edit Recipient
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-slot>
</x-app-layout>
