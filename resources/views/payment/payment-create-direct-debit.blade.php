<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Make Direct Debit Payment') }}
        </h2>
    </x-slot>

    <x-slot name="slot">
        <div class="flex lg:col-start-2 text-slate-800 dark:text-slate-200 m-4 p-4">
            Payments
        </div>
        <hr>

        <div class="container mx-auto p-4" x-data="directDebitPaymentForm()">
            <h1 class="text-2xl font-bold mb-4">Make a payment</h1>
            @auth
                <p class="mb-4">You are logged in as <strong>{{ auth()->user()->name }}</strong></p>
            @endauth
            <p class="mb-4">Please fill your information below</p>

            <form @submit.prevent="submitForm">
                @csrf
                <div class="mb-4">
                    <label for="payer_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payer Name</label>
                    <input type="text" id="payer_name" name="payer_name" x-model="payer_name" required class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm">
                </div>

                <div class="mb-4">
                    <label for="payer_iban" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payer IBAN</label>
                    <input type="text" id="iban" name="iban" x-model="iban" required class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm">
                </div>

                <div class="mb-4">
                    <label for="payer_bic" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payer BIC</label>
                    <input type="text" id="bic" name="bic" x-model="bic" class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm">
                </div>

                <div class="mb-4">
                    <label for="payer_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payer Address</label>
                    <input type="text" id="payer_address" name="payer_address" x-model="payer_address" class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm">
                </div>

                <div class="mb-4">
                    <label for="payer_country" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payer Country</label>
                    <select id="payer_country" name="country" x-model="country" required class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm">
                        <option value="DE">Germany</option>
                        <option value="GB">United Kingdom</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="amount_value" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount</label>
                    <input type="number" step="0.01" id="amount" name="amount" x-model="amount" required class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm">
                </div>

                <div class="mb-4">
                    <label for="amount_currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Currency</label>
                    <input type="text" id="currency" name="currency" x-model="currency" value="EUR" readonly class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm">
                </div>

                <div class="mb-4">
                    <label for="purpose" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Purpose</label>
                    <input type="text" id="purpose" name="purpose" x-model="purpose" class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm">
                </div>

                <div class="mb-4">
                    <label for="execution_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Execution Date</label>
                    <input type="date" id="execution_date" name="execution_date" x-model="execution_date" required class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm">
                </div>

                <div class="mb-4">
                    <label for="batch_booking" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Batch Booking</label>
                    <input id="batch_booking" name="batch_booking_preferred" x-model="batch_booking_preferred"  type="checkbox" class="ml-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Preferred</span>
                </div>

                <div x-show="errorMessage" class="text-red-600">
                    <p x-text="errorMessage"></p>
                </div>

                <div class="flex justify-end">
                    <button type="button"
                            @click="proceedToPayment"
                            :disabled="loading"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                        <span x-show="loading">Processing...</span>
                        <span x-show="!loading">Create Direct Debit</span>
                    </button>
                </div>
            </form>
        </div>

        <script>
            function directDebitPaymentForm() {
                return {
                    payer_name: '',
                    iban: '',
                    bic: '',
                    payer_address: '',
                    country: 'DE',
                    amount: '',
                    currency: 'EUR',
                    purpose: '',
                    execution_date: '',
                    batch_booking_preferred: false,
                    errorMessage: '',
                    loading: false,

                    async proceedToPayment() {
                        this.loading = true;
                        this.errorMessage = '';

                        fetch('/shopify/payment/make-direct-debit-with-webform', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                payer_name: this.payer_name,
                                iban: this.iban,
                                bic: this.bic,
                                payer_address: this.payer_address,
                                country: this.country,
                                amount: this.amount,
                                currency: this.currency,
                                purpose: this.purpose,
                                execution_date: this.execution_date,
                                batch_booking_preferred: this.batch_booking_preferred
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            this.loading = false;  // Stop loading
                            if (data.url) {
                                window.location.href = data.url;  // Redirect to payment form
                            } else if (data.error) {
                                this.errorMessage = `Error: ${data.error}`;  // Show error message
                            }
                        })
                        .catch(error => {
                            this.loading = false;  // Stop loading
                            this.errorMessage = `Failed to initiate payment: ${error.message}`;  // Show general error message
                        });
                    }
                }
            }
        </script>
    </x-slot>
</x-app-layout>
