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

            <!-- Filter Form -->
            <div class="mb-4 flex flex-wrap items-center gap-4">
                <!-- <form class="flex flex-wrap items-center gap-4"> -->
                    <!-- Filter Dropdown -->
                    <div>
                        <input type="hidden" x-model="selectedBankConnectionIds"/>
                        <select
                        id="filter"
                        x-on:change="addBankConnection($event.target.value)"
                        class="border border-gray-300 rounded-lg p-2 w-80 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">Bank Connection</option>
                            <template x-for="bankConnection in bankConnections" :key="bankConnection.id">
                                <option x-bind:value="bankConnection.id" x-text="bankConnection.bankName"></option>
                            </template>
                        </select>
                    </div>

                    <!-- <div>
                        <input type="hidden" x-model="selectedAccountIdsInput"/>
                        <select
                            id="filter"
                            x-on:change="addAccountSelection($event.target.value)"
                            class="border border-gray-300 rounded-lg p-2 w-80 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">Account ID</option>
                            <template x-for="accountId in accountIds" :key="accountId">
                                <option x-bind:value="accountId" x-text="accountId"></option>
                            </template>
                        </select>
                    </div> -->

                    <!-- Search Input -->
                    <div>
                        <input
                            type="text"
                            id="search"
                            x-model="confirmationNumber"
                            placeholder="Purpose"
                            class="border border-gray-300 rounded-lg p-2 w-64 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="button"
                        @click="fetchPage(1)"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                    >
                        Apply Filters
                    </button>
                <!-- </form> -->
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
                        <p>You can search by <strong>Amount, Email or id(name), Confirmation Number</strong> of the order</p>
                        <!-- Searchable Dropdown for Orders -->
                        <div class="max-w-full flex flex-col space-y-2">
                            <input type="text" placeholder="Search orders..." x-model="searchText" class="border p-2 w-full rounded-md dark:bg-slate-700 dark:text-white">
                            <button
                                    type="button"
                                    @click="searchOrders()"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                            >
                                <span x-show="!searchingOrder" class="block">Search Shopify Orders</span>
                                <span x-show="searchingOrder" class="flex justify-center items-center space-x-2">
                                    <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                    </svg>
                                    <span>Searching...</span>
                                </span>
                            </button>

                            <div x-show="searchResult.length > 0" class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-white uppercase bg-slate-900 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th class="px-6 py-3">
                                            Order Details
                                        </th>
                                        <th class="px-4 py-2">
                                            Customer Details
                                        </th>
                                        <th class="px-4 py-2">
                                            Action
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <template x-for="row in searchResult" :key="row.name">
                                        <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-html="row.order_details">
                                            </td>
                                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-html="row.customer_details"></td>
                                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                <button
                                                        type="button"
                                                        @click="linkOrderToTransaction(row.id, selectedTransaction.id)"
                                                        class="ml-2 text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded"
                                                >
                                                    Link
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                    </tbody>
                                </table>
                            </div>

                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-2">

                            <button type="button" @click="showLinkToOrderModal = false"  class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Close</button>
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
                    searchType: '',
                    searchText: '',
                    noSearchResult: false,
                    searchingOrder: false,
                    searchResult: [],

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
                        //this.confirmationNumber = document.getElementById('search').value;

                        // let accountIdArray = this.accountIds.split(',').map(Number);
                        axios.get("{{ route('admin.transactions.get-transactions') }}", {
                            params: { page: page, perPage: this.perPage, confirmationNumber:this.confirmationNumber, search: this.search, currency: this.currency, accountIds: this.selectedBankConnectionIds, maxImportDate: this.maxImportDate }
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

                    searchOrders() {
                        this.searchResult = [];
                        this.searchingOrder = true;

                        axios.get("{{ route('admin.search-orders') }}", {
                            params: { value: this.searchText}
                        })
                            .then(response => {
                                if(response.data.success === false) {
                                    this.noSearchResult = true;
                                } else {
                                    this.searchResult = response.data.orders;
                                    this.searchingOrder = false;
                                }
                            })
                            .catch(error => {
                                this.searchingOrder = true;
                                console.error('Error Searching Orders:', error);
                            });
                    },

                    linkOrder(shopifyOrderId, transactionId) {
                        if(confirm('Are you sure you want to link this order?')) {

                        }
                        console.log(shopifyOrderId, transactionId);
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
                            //alert('An error occurred while fetching transaction data');
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
                    linkOrderToTransaction(shopifyOrderID, transactionId) {
                        // Implement your order-linking functionality here
                        console.log(`Linking Order ID: ${shopifyOrderID} with Transaction ID: ${transactionId}`);

                        // Example API call to link the order with the transaction
                        fetch(`{{ route('shopify.order.linkOrderToTransaction') }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                transaction_id: transactionId,
                                order_id: shopifyOrderID
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.success) {
                                alert('Order linked to transaction and marked paid successfully.', `Message: ${data.message}`);
                                this.showLinkToOrderModal = false;
                                this.fetchPage(this.page);
                            } else {
                                alert(data.message);
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
