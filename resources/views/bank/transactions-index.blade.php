<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            <a
                href="{{ route('bank.index') }}"
                class="rounded-md px-3 py-2 border text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
            >
                <i class="fa-solid fa-circle-left mx-2"></i> Back to Banks
            </a>
            <span class="mx-4 float-right">
                {{ __('Transactions') }}
            </span>
        </h2>
    </x-slot>

    <x-slot name="slot">
        <div class="flex lg:col-start-2 text-slate-800 dark:text-slate-200 m-4 p-4">
            Transactions
        </div>
        <hr>

        <div class="-mx-3 flex flex-1 m-3 p-3">
            <nav class="-mx-3 flex flex-1 justify-start m-3">
                <a
                    href="{{ route('admin.bank.import-bank-connection') }}"
                    class="rounded-md border px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    Import Bank Connection
                </a>
            </nav>
        </div>
        <hr>

        <!-- Table with Filter -->
        <div class="my-4 w-full text-nowrap"  x-data="transactionData()">
            <!-- Filters Form -->
            <div class="flex flex-col lg:flex-row gap-4">
                <!-- <input
                    type="text"
                    placeholder="Search"
                    x-model="search"
                    class="w-full lg:w-1/4 px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-300 dark:bg-slate-800 dark:text-slate-200 dark:border-slate-600"
                />
                <input
                    type="text"
                    placeholder="Currency"
                    x-model="currency"
                    class="w-full lg:w-1/4 px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-300 dark:bg-slate-800 dark:text-slate-200 dark:border-slate-600"
                /> -->

                <div class="flex flex-1 mx-2 items-center" x-data="{
                    selectedIds: [],
                    options: [],
                    init() {
                        this.options = [
                            @foreach($bankConnections->connections as $bankConnection)
                                @foreach($bankConnection->accountIds as $accountId)
                                    '{{ $accountId }}',
                                @endforeach
                            @endforeach
                        ];
                    },
                    addSelection(id) {
                        if (!this.selectedIds.includes(id)) {
                            this.selectedIds.push(id);
                        }
                        this.updateInput();
                    },
                    removeSelection(id) {
                        this.selectedIds = this.selectedIds.filter(i => i !== id);
                        this.updateInput();
                    },
                    updateInput() {
                        this.accountIds = this.selectedIds.join(',');
                    }
                }">
                    <label for="accountIds" class="text-sm">Account IDs</label>
                    <input type="hidden" x-model="accountIds"/>
                    <select x-on:change="addSelection($event.target.value)" class="mx-4 px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-300 dark:bg-slate-800 dark:text-slate-200 dark:border-slate-600">
                        <option value="">Select account ID</option>
                        <template x-for="option in options" :key="option">
                            <option x-bind:value="option" x-text="option"></option>
                        </template>
                    </select>

                    <div class="mt-2 flex gap-2">
                        <template x-for="id in selectedIds" :key="id">
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 border rounded-md bg-indigo-100 dark:bg-slate-700 dark:text-slate-200" x-text="id"></span>
                                <button type="button" @click="removeSelection(id)" class="text-red-600 hover:text-red-800">&times;</button>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- <input
                    type="date"
                    placeholder="Min Import Date"
                    x-model="minImportDate"
                    class="w-full lg:w-1/4 px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-300 dark:bg-slate-800 dark:text-slate-200 dark:border-slate-600"
                />
                <input
                    type="date"
                    placeholder="Max Import Date"
                    x-model="maxImportDate"
                    class="w-full lg:w-1/4 px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-300 dark:bg-slate-800 dark:text-slate-200 dark:border-slate-600"
                /> -->
                <button
                    @click="fetchPage(1)"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none"
                >
                    Apply Filters
                </button>
            </div>

            <div class="w-full">
                <div class="w-full -mx-3 m-3 p-3">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-slate-900 border border-slate-800 dark:border-slate-100">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">ID</th>
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">Account ID</th>
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">Amount</th>
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">Currency</th>
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">Purpose</th>
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">Counterpart Name</th>
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="transaction in transactions" :key="transaction.id">
                                    <tr class="bg-slate-50 dark:bg-slate-800 border-b  border-slate-900 dark:border-slate-50 text-nowrap">
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100" x-text="transaction.id"></td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100" x-text="transaction.accountId"></td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100" x-text="numberFormat(transaction.amount)"></td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100" x-text="transaction.currency"></td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100" x-text="transaction.purpose"></td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100" x-text="transaction.counterpartName"></td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100" x-text="formatDate(transaction.valueDate)"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-sm text-slate-500">
                        Showing <span x-text="page"></span> of <span x-text="pageCount"></span> pages
                        <select
                            x-model="perPage"
                            class="w-full lg:w-1/4 ml-2 px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-300 dark:bg-slate-800 dark:text-slate-200 dark:border-slate-600"
                            x-on:change="fetchPage(1)"
                        >
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        transactions per page
                    </div>

                    <div class="space-x-2">
                        <button
                            class="px-4 py-2 bg-slate-300 text-slate-700 rounded"
                            :disabled="page <= 1"
                            @click="previousPage()">
                            Previous
                        </button>
                        <button
                            class="px-4 py-2 bg-slate-300 text-slate-700 rounded"
                            :disabled="page >= pageCount"
                            @click="nextPage()">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function transactionData() {
                return {
                    transactions: @json($transactions->transactions),
                    page: {{ $transactions->paging->page }},
                    perPage: {{ $transactions->paging->perPage }},
                    pageCount: {{ $transactions->paging->pageCount }},
                    totalCount: {{ $transactions->paging->totalCount }},
                    search : '',
                    currency : '',
                    accountIds : '',
                    accountIds: '',
                    minImportDate: '',
                    maxImportDate: '',

                    fetchPage(page) {
                        showLoading();
                        // let accountIdArray = this.accountIds.split(',').map(Number);
                        axios.get("{{ route('admin.bank.get-transactions') }}", {
                            params: { page: page, perPage: this.perPage, search: this.search, currency: this.currency, accountIds: this.accountIds, maxImportDate: this.maxImportDate }
                        })
                        .then(response => {
                            this.transactions = response.data.transactions;
                            this.page = response.data.paging.page;
                            this.pageCount = response.data.paging.pageCount;
                            this.totalCount = response.data.paging.totalCount;
                            hideLoading();
                        })
                        .catch(error => {
                          console.error('Error fetching transactions:', error);
                          hideLoading();
                        });
                    },

                    previousPage() {
                        if (this.page > 1) {
                            this.page--;
                            this.fetchPage(this.page);
                        }
                    },

                    nextPage() {
                        if (this.page < this.pageCount) {
                            this.page++;
                            this.fetchPage(this.page);
                        }
                    },

                    numberFormat(amount) {
                        return new Intl.NumberFormat().format(amount);
                    },

                    formatDate(dateString) {
                        return new Date(dateString).toISOString().split('T')[0];
                    }
                }
            }
        </script>
    </x-slot>
</x-app-layout>
