<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Bank Information') }}
        </h2>
    </x-slot>

    <x-slot name="slot">
        <hr>
        <div class="-mx-3 flex flex-1 m-3 p-3">
            <nav class="-mx-3 flex flex-1 justify-start m-3">
                <a
                    href="{{ route('admin.bank.import-bank-connection') }}"
                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    Import Bank Connection
                </a>
                <a
                    href="{{ url('/transactions') }}"
                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    Transactions
                </a>
                <a
                    href="{{ url('settings/finapi-payment-recipient') }}"
                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    Edit Payment Recipient
                </a>
            </nav>
        </div>
        <hr>

        <!-- Accordion container for bank connections -->
        <div x-data="{ openConnection: null }" class="-mx-3 m-3 p-3">
            <div class="space-y-4">
                @if(isset($bankConnections))
                    <!-- Iterate over the bank connections -->
                    @foreach ($bankConnections as $bankConnection)
                        @php
                            $connection = json_decode($bankConnection->data);
                        @endphp
                        <div class="border-b">
                            <!-- Accordion Header (Bank Connection Name) -->
                            <button
                                x-on:click="openConnection = openConnection === {{ $connection->id }} ? null : {{ $connection->id }}"
                                class="w-full text-left flex justify-between items-center p-3 text-lg font-semibold text-black dark:text-slate-200 bg-gray-100 hover:bg-gray-200 rounded-md"
                            >
                                <span>{{ $connection->name }}</span>
                                <svg x-bind:class="openConnection === {{ $connection->id }} ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="openConnection === {{ $connection->id }}" x-transition class="pl-6 pt-2 pb-4 space-y-2 bg-gray-50">
                                <!-- Bank Information -->
                                <div>
                                    <p><strong>FinApi Bank Connection Id:</strong> {{ $connection->id }}</p>
                                    <p><strong>Bank Name:</strong> {{ $connection->bank->name }}</p>
                                    <p><strong>BLZ:</strong> {{ $connection->bank->blz }}</p>
                                    <p><strong>Bank Group:</strong> {{ $connection->bank->bankGroup->name }}</p>
                                    <img src="{{ $connection->bank->logo->url }}" alt="Bank Logo" class="w-32 h-auto mt-2" />
                                </div>

                                <div>
                                    <strong>Interfaces:</strong>
                                    <ul class="list-disc pl-5">
                                        @foreach ($connection->bank->interfaces as $interface)
                                            <li>{{ $interface->bankingInterface }} - Login Credentials:
                                                @foreach ($interface->loginCredentials as $credential)
                                                    <span>{{ $credential->label }}{{ isset($credential->value) ? ': ' . $credential->value : '' }}</span><br>
                                                @endforeach
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div>
                                    <strong>Account IDs:</strong>
                                    <ul class="list-disc pl-5">
                                        @foreach ($connection->accountIds as $accountId)
                                            <li>{{ $accountId }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <hr>
    </x-slot>
</x-app-layout>
