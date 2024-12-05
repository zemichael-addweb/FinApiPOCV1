<x-app-layout>
    <x-slot name="slot">

        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-title-md2 font-bold text-black dark:text-white">
            Transactions
            </h1>
        </div>

        @if(!$bankConnections || !$transactions)
            <div class="flex flex-1 justify-center items-center h-96">
                <div class="text-center">
                    <h2 class="text-2xl font-semibold text-slate-800 dark:text-slate-200">No Transactions Found or Failed to fetch</h2>
                    <p class="text-lg text-slate-600 dark:text-slate-400">Please import a bank connection to view transactions or contact system admin.</p>
                </div>
            </div>
        @endif

        <!-- Table with Filter -->
        <div class="my-4 w-full text-nowrap"  x-data="transactionData()">
            <!-- Filters Form -->
            <div>
                <div class="flex flex-col lg:flex-row gap-4">
                    <div class="mb-3 flex flex-col lg:flex-row overflow-auto gap-4">
                        <!-- Bank Connections Filter -->
                        <div class="flex flex-1 mx-2 items-center">
                            <label for="selectedBankConnectionIds" class="text-sm">Bank Connections</label>
                            <input type="hidden" x-model="selectedBankConnectionIds"/>
                            <select x-on:change="addBankConnection($event.target.value)" class="mx-4 px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-300 dark:bg-slate-800 dark:text-slate-200 dark:border-slate-600">
                                <option value="">Select Bank Connection</option>
                                <template x-for="bankConnection in bankConnections" :key="bankConnection.id">
                                    <option x-bind:value="bankConnection.id" x-text="bankConnection.bankName"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Account IDs Filter -->
                        <div class="flex flex-1 mx-2 items-center">
                            <label for="selectedAccountIds" class="text-sm">Account IDs</label>
                            <input type="hidden" x-model="selectedAccountIdsInput"/>
                            <select x-on:change="addAccountSelection($event.target.value)" class="mx-4 px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-300 dark:bg-slate-800 dark:text-slate-200 dark:border-slate-600">
                                <option value="">Select Account ID</option>
                                <template x-for="accountId in accountIds" :key="accountId">
                                    <option x-bind:value="accountId" x-text="accountId"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Fetch if there is confirmation number -->
                        <div class="flex flex-1 mx-2 items-center">
                            <label for="confirmationNumber" class="text-sm">Confirmation Number</label>
                            <input type="text" x-model="confirmationNumber" class="mx-4 px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-300 dark:bg-slate-800 dark:text-slate-200 dark:border-slate-600">
                        </div>
                    </div>

                    <!-- Apply Filters Button -->
                    <button
                        @click="fetchPage(1)"
                        class="bg-indigo-600 text-white px-4 py-2 ms-auto rounded-md hover:bg-indigo-700 focus:outline-none"
                    >
                        Apply Filters
                    </button>
                </div>

                <!-- Selected Filters Display (Badges) -->
                <div class="mt-4 flex flex-wrap gap-2">
                    <!-- Selected Bank Connections -->
                    <template x-for="name in selectedBankConnectionNames" :key="name">
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-1 border rounded-md bg-indigo-100 dark:bg-slate-700 dark:text-slate-200" x-text="name"></span>
                            <button type="button" @click="removeBankConnection(name)" class="text-red-600 hover:text-red-800">&times;</button>
                        </div>
                    </template>
                </div>
                <!-- Selected Filters Display (Badges) -->
                <div class="mt-4 flex flex-wrap gap-2">
                    <!-- Selected Account IDs -->
                    <template x-for="id in selectedAccountIds" :key="id">
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-1 border rounded-md bg-indigo-100 dark:bg-slate-700 dark:text-slate-200" x-text="id"></span>
                            <button type="button" @click="removeAccountSelection(id)" class="text-red-600 hover:text-red-800">&times;</button>
                        </div>
                    </template>
                </div>
            </div>


            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-white uppercase bg-slate-900 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">#ID</th>
                            <th scope="col" class="px-6 py-3">Account ID</th>
                            <th scope="col" class="px-6 py-3">Amount</th>
                            <th scope="col" class="px-6 py-3">Purpose</th>
                            <th scope="col" class="px-6 py-3">Counterpart Name</th>
                            <th scope="col" class="px-6 py-3">Date</th>
                            <th scope="col" class="px-6 py-3">View</th>
                            <th scope="col" class="px-6 py-3">Link To Order</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="transaction in transactions" :key="transaction.id">
                            <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-text="transaction.id"></td>
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-text="transaction.accountId"></td>
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-text="numberFormat(transaction.amount) +' '+transaction.currency"></td>
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-text="transaction.purpose"></td>
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-text="transaction.counterpartName"></td>
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-text="transaction.valueDate"></td>
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <button
                                        @click="viewTransaction(transaction.id)"
                                        class="ml-2 text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded"
                                    >
                                        View
                                    </button>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <button
                                        @click="linkToOrder(transaction.id)"
                                        class="ml-2 text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded"
                                    >
                                        Link to Order
                                    </button>
                                </td>
                            </tr>
                        </template>

                    </tbody>

                </table>
            </div>
            <div class="w-full">
                <div x-show="transactions" class="w-full -mx-3 m-3 p-3">
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
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">View</th>
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">Link To Order</th>
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
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100 text-nowrap">
                                            <button
                                                @click="viewTransaction(transaction.id)"
                                                class="ml-2 text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded"
                                            >
                                                View
                                            </button>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100">
                                            <button
                                                @click="linkToOrder(transaction.id)"
                                                class="ml-2 text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded"
                                            >
                                                Link to Order
                                            </button>
                                        </td>
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

                <!-- View Transaction Modal (hidden by default) -->
                <div
                    x-show="showViewTransactionModal"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 mt-6 overflow-y-scroll"
                    @keydown.escape.window="showViewTransactionModal = false"
                >




                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-xl max-w-3xl w-full p-6 space-y-4">

                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Transaction Details</h2>
                            <button @click="showViewTransactionModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                                &times;
                            </button>
                        </div>
                        <hr class="my-2">

                        <!-- Modal content (webform details) -->
                        <div class="p-2">
                            <!-- Individual Fields -->
                            <p><strong>ID:</strong> <span x-text="selectedTransaction.id"></span></p>
                            <p><strong>Account ID:</strong> <span x-text="selectedTransaction.accountId"></span></p>
                            <p><strong>Amount:</strong> <span x-text="numberFormat(selectedTransaction.amount)"></span></p>
                            <p><strong>Currency:</strong> <span x-text="selectedTransaction.currency"></span></p>
                            <p><strong>Purpose:</strong> <span x-text="selectedTransaction.purpose"></span></p>
                            <p><strong>Counterpart Name:</strong> <span x-text="selectedTransaction.counterpartName"></span></p>
                            <p><strong>Counterpart Account Number:</strong> <span x-text="selectedTransaction.counterpartAccountNumber"></span></p>
                            <p><strong>Counterpart IBAN:</strong> <span x-text="selectedTransaction.counterpartIban"></span></p>
                            <p><strong>Counterpart BLZ:</strong> <span x-text="selectedTransaction.counterpartBlz"></span></p>
                            <p><strong>Counterpart BIC:</strong> <span x-text="selectedTransaction.counterpartBic"></span></p>
                            <p><strong>Counterpart Bank Name:</strong> <span x-text="selectedTransaction.counterpartBankName"></span></p>
                            <p><strong>Value Date:</strong> <span x-text="formatDate(selectedTransaction.valueDate)"></span></p>
                            <p><strong>Bank Booking Date:</strong> <span x-text="formatDate(selectedTransaction.bankBookingDate)"></span></p>
                            <p><strong>FinAPI Booking Date:</strong> <span x-text="formatDate(selectedTransaction.finapiBookingDate)"></span></p>
                            <p><strong>Type:</strong> <span x-text="selectedTransaction.type"></span></p>
                            <p><strong>Type Code ZKA:</strong> <span x-text="selectedTransaction.typeCodeZka"></span></p>
                            <p><strong>Is Potential Duplicate:</strong> <span x-text="selectedTransaction.isPotentialDuplicate ? 'Yes' : 'No'"></span></p>
                            <p><strong>Is Adjusting Entry:</strong> <span x-text="selectedTransaction.isAdjustingEntry ? 'Yes' : 'No'"></span></p>
                            <p><strong>Is New:</strong> <span x-text="selectedTransaction.isNew ? 'Yes' : 'No'"></span></p>
                            <p><strong>Import Date:</strong> <span x-text="formatDateTime(selectedTransaction.importDate)"></span></p>

                            <!-- Pretty Printed JSON Data -->
                            <!-- <p><strong>Data [JSON]:</strong></p>
                            <pre x-text="JSON.stringify(selectedTransaction, null, 2)" class="whitespace-pre-wrap"></pre> -->
                        </div>

                        <div class="flex justify-end space-x-2">
                            <button @click="showViewTransactionModal = false" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Close</button>
                        </div>
                    </div>
                </div>

                <!-- Link to Order Modal -->
                <div
                    x-show="showLinkToOrderModal"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                    @keydown.escape.window="showLinkToOrderModal = false"
                >
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-xl max-w-3xl w-full p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Transaction Details</h2>
                            <button @click="showLinkToOrderModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                                &times;
                            </button>
                        </div>

                        <!-- Transaction Details -->

                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-white uppercase bg-slate-900 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">#ID</th>
                                        <th scope="col" class="px-6 py-3">Account ID</th>
                                        <th scope="col" class="px-6 py-3">Amount</th>
                                        <th scope="col" class="px-6 py-3">Purpose</th>
                                        <th scope="col" class="px-6 py-3">Counterpart Name and Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-text="selectedTransaction.id"></td>
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-text="selectedTransaction.accountId"></td>
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-text="numberFormat(selectedTransaction.amount) +' '+selectedTransaction.currency"></td>
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-text="selectedTransaction.purpose"></td>
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-text="selectedTransaction.counterpartName + '('+formatDate(selectedTransaction.valueDate)+')'"></td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>

                        <p><strong>Search for orders to match and link</strong></p>
                        <p>You can search by <strong>Amount, Email or id(name)</strong> of the order</p>
                        <!-- Searchable Dropdown for Orders -->
                        <div class="max-w-full flex flex-col space-y-2">
                            <label class="block text-slate-700 dark:text-slate-300">Select Order</label>
                            <input type="text" placeholder="Search orders..." x-model="orderSearch" class="border p-2 w-full rounded-md dark:bg-slate-700 dark:text-white">


                            <select  x-model="selectedOrderId" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                <option value="" disabled>Select an order</option>
                                <template x-for="order in sortedOrders" :key="order.id">
                                    <option :value="order.id" x-text="`Amount: €${order.data.currentTotalPriceSet.shopMoney.amount} - ID: ${order.name} - Email: ${order.email}`"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-2">

                            <button type="button" @click="showLinkToOrderModal = false"  class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Close</button>

                            <button type="button" @click="linkOrderToTransaction()" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800" :disabled="!selectedOrderId">Link Order</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function transactionData() {
                return {
                    transactions: @if(isset($transactions->transactions))@json($transactions->transactions)@else "[]" @endif,
                    page: {{ isset($transactions->paging) ? $transactions->paging->page : "1" }},
                    perPage: {{ isset($transactions->paging) ? $transactions->paging->perPage : "20"  }},
                    pageCount: {{ isset($transactions->paging) ? $transactions->paging->pageCount : "0"  }},
                    totalCount: {{ isset($transactions->paging) ? $transactions->paging->totalCount : "0"  }},
                    confirmationNumber : '',
                    search : '',
                    currency : '',
                    minImportDate: '',
                    maxImportDate: '',

                    showLinkToOrderModal: false,
                    localOrders: [],
                    orderSearch: '',
                    selectedOrderId: '',

                    showViewTransactionModal: false,
                    selectedTransaction: {},

                    selectedBankConnectionNames: [],
                    bankConnections: [],
                    selectedBankConnectionIds: '',

                    selectedAccountIds: [],
                    accountIds: [],
                    selectedAccountIdsInput: [],

                    init() {
                        // Initialize bank connections
                        this.bankConnections = [
                            @foreach($bankConnections as $bankConnection)
                                {
                                    'id': '{{ $bankConnection->id }}',
                                    'bankName': '{{ $bankConnection->bank_name }}',
                                    'accountIds': [
                                        @foreach(json_decode($bankConnection->data)->accountIds as $accountId)
                                            '{{ $accountId }}',
                                        @endforeach
                                    ],
                                },
                            @endforeach
                        ];

                        // Initialize account IDs
                        this.accountIds = [
                            @foreach($bankConnections as $bankConnection)
                                @foreach(json_decode($bankConnection->data)->accountIds as $accountId)
                                    '{{ $accountId }}',
                                @endforeach
                            @endforeach
                        ];
                    },

                    // Bank Connection Selection
                    addBankConnection(id) {
                        const connection = this.bankConnections.find(connection => connection.id === id);
                        if (connection && !this.selectedBankConnectionNames.includes(connection.bankName)) {
                            this.selectedBankConnectionNames.push(connection.bankName);
                        }
                        this.selectedAccountIds = this.selectedAccountIds.concat(connection.accountIds.filter(accountId => !this.selectedAccountIds.includes(accountId)));
                        this.updateBankConnectionInput();
                    },
                    removeBankConnection(bankName) {
                        this.selectedBankConnectionNames = this.selectedBankConnectionNames.filter(connectionName => connectionName !== bankName);
                        this.selectedAccountIds = this.selectedAccountIds.filter(accountId => !this.bankConnections.find(connection => connection.bankName === bankName).accountIds.includes(accountId));
                        this.updateBankConnectionInput();
                    },
                    updateBankConnectionInput() {
                        this.selectedBankConnectionIds = this.bankConnections
                            .filter(connection => this.selectedBankConnectionNames.includes(connection.bankName))
                            .map(connection => connection.id)
                            .join(',');
                    },

                    // Account ID Selection
                    addAccountSelection(id) {
                        if (!this.selectedAccountIds.includes(id)) {
                            this.selectedAccountIds.push(id);
                        }
                        this.updateAccountInput();
                    },
                    removeAccountSelection(id) {
                        this.selectedAccountIds = this.selectedAccountIds.filter(i => i !== id);
                        this.updateAccountInput();
                    },
                    updateAccountInput() {
                        this.selectedAccountIdsInput = this.selectedAccountIds.join(',');
                    },

                    fetchPage(page) {
                        showLoading();
                        // let accountIdArray = this.accountIds.split(',').map(Number);
                        axios.get("{{ route('admin.transactions.get-transactions') }}", {
                            params: { page: page, perPage: this.perPage, confirmationNumber:this.confirmationNumber, search: this.search, currency: this.currency, accountIds: this.selectedAccountIds, maxImportDate: this.maxImportDate }
                        })
                        .then(response => {
                            console.log('Transactions:', response.data.transactions)
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
                    },
                    viewTransaction(transactionId) {
                        showLoading();
                        let token = document.head.querySelector('meta[name="csrf-token"]').content;

                        // Fetch webform data from the server
                        fetch(`/transaction/${transactionId}`, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log(data);
                            if (data && !data.error) {
                                this.selectedTransaction = data;
                                this.showViewTransactionModal = true;
                            } else {
                                alert('Failed to fetch transaction data');
                            }
                            hideLoading();
                        })
                        .catch(error => {
                            console.error('Error fetching transaction data:', error);
                            alert('An error occurred while fetching transaction data');
                            hideLoading();
                        });
                    },
                    get sortedOrders() {
                        // Convert search to lowercase for case-insensitive comparison
                        const search = this.orderSearch.toLowerCase();

                        return this.localOrders
                            .filter(order => {
                                const amount = order.data.currentTotalPriceSet.shopMoney.amount.toString();
                                return (
                                    order.name.toLowerCase().includes(search) ||
                                    order.email.toLowerCase().includes(search) ||
                                    amount.includes(search)
                                );
                            })
                            .sort((a, b) => parseFloat(b.data.currentTotalPriceSet.shopMoney.amount) - parseFloat(a.data.currentTotalPriceSet.shopMoney.amount));
                    },
                    linkToOrder(transactionId) {
                        showLoading();
                        let token = document.head.querySelector('meta[name="csrf-token"]').content;

                        // Fetch webform data from the server
                        fetch(`/transaction/${transactionId}`, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log('fetched transaction : ', data);
                            if (data && !data.error) {
                                this.selectedTransaction = data;
                                this.showLinkToOrderModal = true;
                                // TODO - Add code to link transaction to order
                                // Fetch local orders from the server
                                fetch(`{{ route ('shopify.order.get-local-orders') }}`, {
                                    method: 'GET',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': token
                                    },
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data && data.length > 0) {
                                        this.localOrders = data;
                                    } else {
                                        alert('Failed to fetch local orders');
                                    }
                                    hideLoading();
                                })
                                .catch(error => {
                                    console.error('Error fetching local orders:', error);
                                    alert('An error occurred while fetching local orders');
                                    hideLoading();
                                });
                            } else {
                                alert('Failed to fetch transaction data');
                            }
                            hideLoading();
                        })
                        .catch(error => {
                            console.error('Error fetching transaction data:', error);
                            alert('An error occurred while fetching transaction data');
                            hideLoading();
                        });
                    },
                    linkOrderToTransaction() {
                        // Implement your order-linking functionality here
                        console.log(`Linking Order ID: ${this.selectedOrderId} with Transaction ID: ${this.selectedTransaction.id}`);

                        // Example API call to link the order with the transaction
                        fetch(`{{ route('shopify.order.linkOrderToTransaction') }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                transaction_finapi_id: this.selectedTransaction.id,
                                order_shopify_id: this.selectedOrderId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.success) {
                                alert('Order linked to transaction and marked paid successfully.', `Message: ${data.message}`);
                                this.showLinkToOrderModal = false;
                                this.fetchPage(this.page);
                            } else {
                                alert('Failed to link order');
                            }
                        })
                        .catch(error => {
                            console.error('Error linking order:', error);
                            alert('An error occurred\ while linking the order');
                        });
                    }
                }
            }
        </script>
    </x-slot>
</x-app-layout>
