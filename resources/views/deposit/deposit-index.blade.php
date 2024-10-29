<x-app-layout>
    <x-slot name="header">
        {{ __('Deposit') }}
    </x-slot>

    <x-slot name="slot">
        <div class="-mx-3 flex flex-1 m-3 p-3">
            <nav class="-mx-3 flex flex-1 justify-start m-3">
                <a
                    href="{{ url('/payments/create') }}"
                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    Make Payment
                </a>
                <a
                    href="{{ url('/deposits/create') }}"
                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    Make Deposit
                </a>
            </nav>
        </div>
        <hr>
        <div class="flex lg:col-start-2 text-slate-800 dark:text-slate-200 m-4 p-4">
            Deposit Table
        </div>
        <div class="my-4 w-full text-nowrap" x-data="depositData()">
            <!-- error message -->
            <div x-text="errorMessage" x-show="errorMessage" class="text-wrap bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">Error!</div>
            <!-- success message -->
            <div x-text="successMessage" x-show="successMessage" class="text-wrap bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">Success!</div>
            <!-- Deposits Table -->
            <div class="w-full">
                <div class="w-full -mx-3 m-3 p-3">
                    <div class="overflow-x-auto">
                        <table x-show="deposits && deposits.length > 0" class="min-w-full bg-white dark:bg-slate-900 border border-slate-800 dark:border-slate-100">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">ID</th>
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">Finapi ID</th>
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">IBAN</th>
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">Amount</th>
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">Type</th>
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">Status V2</th>
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">Request Date</th>
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">Refresh Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="deposit in deposits" :key="deposit.id">
                                    <tr class="bg-slate-50 dark:bg-slate-800 border-b border-slate-900 dark:border-slate-50">
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100" x-text="deposit.id"></td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100" x-text="deposit.finapiId"></td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100" x-text="deposit.iban"></td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100" x-text="deposit.amount"></td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100" x-text="deposit.type"></td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100" x-bind:class="deposit.statusV2 == 'DISCARDED' ? 'bg-red-500' : (deposit.statusV2 == 'SUCCESSFUL' ? 'bg-green-500' : 'bg-yellow-500')" x-text="deposit.statusV2"></td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100" x-text="formatDate(deposit.requestDate)"></td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100">
                                            <button
                                                @click="refreshDepositInformation(deposit.finapiId)"
                                                class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none"
                                            >
                                                Refresh / Verify
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                        <div x-show="!deposits || deposits.length === 0" class="text-wrap bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                            No Deposits Found!
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function depositData() {
                return {
                    errorMessage: "",
                    successMessage: "",
                    deposits: @json($finapiDeposits),
                    refreshDepositInformation(finapiId) {
                        showLoading();
                        fetch(`{{ route('admin.deposit.getdeposit') }}?id=${finapiId}`, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                        })
                            .then(response => response.json())
                            .then(data => {
                                hideLoading();
                                if (data.error) {
                                    this.errorMessage = data.error;
                                    setTimeout(() => {
                                        this.errorMessage = "";
                                    }, 3000);
                                } else {
                                    this.errorMessage = "";
                                    const index = this.deposits.findIndex(deposit => deposit.finapiId === finapiId);
                                    const fetchedDeposit = data.deposits[0];
                                    fetchedDeposit.finapiId = finapiId;
                                    fetchedDeposit.id = this.deposits[index].id;
                                    this.deposits[index] = fetchedDeposit;
                                    this.successMessage = "Deposit information refreshed successfully!";
                                    setTimeout(() => {
                                        this.successMessage = "";
                                    }, 3000);
                                }
                            })
                            .catch(error => {
                                console.error('Error refreshing deposit information:', error);
                                hideLoading();
                            });
                    },
                    formatDate(date) {
                        const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
                        return new Date(date).toLocaleDateString('en-US', options);
                    }
                };
            }
        </script>
    </x-slot>
</x-app-layout>
