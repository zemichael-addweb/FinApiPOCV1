<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            <a
                href="{{ route('admin.bank.transactions') }}"
                class="rounded-md px-3 py-2 border text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
            >
                <i class="fa-solid fa-receipt mx-2"></i> Transactions
            </a>
            <span class="mx-4 float-right">
                {{ __('Payments') }}
            </span>
        </h2>
    </x-slot>

    <x-slot name="slot">
        <div class="flex lg:col-start-2 text-slate-800 dark:text-slate-200 m-4 p-4">
            Payments List
        </div>
        <hr>
        <div class="my-4 w-full text-nowrap" x-data="paymentData()">
            <!-- error message -->
            <div x-text="errorMessage" x-show="errorMessage" class="text-wrap bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">Error!</div>
            <!-- success message -->
            <div x-text="successMessage" x-show="successMessage" class="text-wrap bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">Success!</div>
            <!-- Payments Table -->
            <div class="w-full">
                <div class="w-full -mx-3 m-3 p-3">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-slate-900 border border-slate-800 dark:border-slate-100">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">ID</th>
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">IBAN</th>
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">Amount</th>
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">Type</th>
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">Status V2</th>
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">Request Date</th>
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">Refresh Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="payment in payments" :key="payment.id">
                                    <tr class="bg-slate-50 dark:bg-slate-800 border-b border-slate-900 dark:border-slate-50">
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100" x-text="payment.id"></td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100" x-text="payment.iban"></td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100" x-text="payment.amount"></td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100" x-text="payment.type"></td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100" x-text="payment.statusV2"></td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100" x-text="formatDate(payment.requestDate)"></td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100">
                                            <button
                                                @click="refreshPaymentInformation(payment.finapiId)"
                                                class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none"
                                            >
                                                Refresh / Verify
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function paymentData() {
                return {
                    errorMessage: "",
                    successMessage: "",
                    payments: @json($finapiPayments),
                    refreshPaymentInformation(finapiId) {
                      showLoading();
                      fetch(`{{ route('admin.payment.getpayment') }}?id=${finapiId}`, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                        })
                            .then(response => response.json())
                            .then(data => {
                                hideLoading();
                                if(data.error){
                                  this.errorMessage = data.error;
                                  setTimeout(() => {
                                    this.errorMessage = "";
                                  }, 3000);
                                } else {
                                  this.errorMessage = "";
                                  const index = this.payments.findIndex(payment => payment.finapiId === finapiId);
                                  const fetchedPayment = data.payments[0];
                                  fetchedPayment.finapiId = finapiId;
                                  console.log('index', this.payments[index]);
                                  console.log('fetched', fetchedPayment);
                                  fetchedPayment.id = this.payments[index].id;
                                  this.payments[index] = fetchedPayment;
                                  this.successMessage = "Payment information refreshed successfully!";
                                  setTimeout(() => {
                                    this.successMessage = "";
                                  }, 3000);
                                }
                            })
                            .catch(error => {
                                console.error('Error refreshing payment information:', error);
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
