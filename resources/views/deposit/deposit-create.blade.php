
<x-app-layout>
    <x-slot name="header">
        {{ __('Make Deposit') }}
    </x-slot>

    <x-slot name="slot">
        <div class="container mx-auto p-4" x-data="paymentForm()">
            <h1 class="text-2xl font-bold mb-4">Make a Deposit</h1>
            <p class="mb-4">To make a payment, please specify the amount bellow</p>

            <!-- Amount -->
            <div class="my-4">
                <label for="amount" class="block text-sm font-medium text-slate-700">Amount</label>
                <input type="text" name="amount" id="amount" x-model="amount" class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <!-- Currency -->
            <div class="my-4">
                <label for="currency" class="block text-sm font-medium text-slate-700">Currency</label>
                <input readonly type="text" name="currency" id="currency" x-model="currency" class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <!-- Button and Payment Info -->
            <div class="mt-4">
                <button 
                    @click="redirectToPayment" 
                    :disabled="loading || (!amount || !currency || amount == '' || currency == '')" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    x-text="!amount || !currency || amount == '' || currency == '' ? 'Please Fill All Fields' : 'Proceed to Payment'">
                </button>
            </div>

            <!-- Display Error -->
            <div x-show="errorMessage" class="mt-4 text-red-500">
                <p><strong>Error:</strong> <span x-text="errorMessage || 'N/A'"></span></p>
            </div>
        </div>

        <!-- Alpine.js Script -->
        <script>
            function paymentForm() {
                return {
                    amount: '',
                    currency: 'EUR', // ! currency check
                    loading: false,
                    errorMessage: '',
                    orderFetched: false,
                    redirectToPayment() {
                        const amount = this.amount
                        const currency = this.currency

                        if (!amount || !currency) {
                            this.errorMessage = 'Unable to proceed, missing amount or currency.';
                            return;
                        }

                        fetch('/deposits/redirect-to-deposit-form', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ amount, currency })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.url) {
                                window.location.href = data.url; // Redirect to payment form
                            } else {
                                this.errorMessage = 'Failed to initiate payment. Please try again.';
                            }
                        })
                        .catch(() => {
                            this.errorMessage = 'Failed to initiate payment. Please try again.';
                        });
                    }
                }
            }
        </script>
    </x-slot>
</x-app-layout>