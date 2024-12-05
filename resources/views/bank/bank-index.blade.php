<x-app-layout>
    <x-slot name="slot">

        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-title-md2 font-bold text-black dark:text-white">
                My Bank Connections
            </h1>

            <nav>
                <a href="{{ route('admin.bank.import-bank-connection') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">New Bank Connection</a>
            </nav>
        </div>


        <!-- <div class="-mx-3 flex flex-1 m-3 p-3">
            <nav class="-mx-3 flex flex-1 justify-start m-3">
            <a href="{{ route('admin.bank.import-bank-connection') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Read more</a>

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
        </div> -->



        <!-- Accordion container for bank connections -->
        <div x-data="{ openConnection: null }" class="-mx-3 m-3 p-3">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-white uppercase bg-slate-900 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                #ID
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Bank Name (BLZ)
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Bank Group
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Bank Logo
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Account IDs
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($bankConnections))
                            @foreach ($bankConnections as $bankConnection)
                                @php
                                    $connection = json_decode($bankConnection->data);
                                @endphp
                                <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $connection->id }}
                                    </td>
                                    <td class="px-6 py-4">
                                    {{ $connection->bank->name }} ({{ $connection->bank->blz }})
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $connection->bank->bankGroup->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if(isset($connection->bank->logo->url))
                                            <img src="{{ $connection->bank->logo->url }}" alt="Bank Logo" class="w-32 h-auto mt-2" />
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ implode(', ', $connection->accountIds) }}
                                    </td>
                                </tr>
                            @endforeach

                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </x-slot>
</x-app-layout>
