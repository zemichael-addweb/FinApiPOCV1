<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-grow">
            <span class="">{{ __('Payment') }}</span>
            <a class="ms-auto" href="{{route('payments.create')}}">Make Payment</a>
        </div>
    </x-slot>

    <x-slot name="slot">
        <div class="flex lg:col-start-2 text-slate-800 dark:text-slate-200 m-4 p-4">
            Payments
        </div>
        <hr>

        <div class="container mx-auto p-4" x-data="paymentForm()">
            <h1 class="text-2xl font-bold mb-4">Make a payment</h1>
            @auth
                <p class="mb-4">You are logged in as <strong>{{ auth()->user()->name }}</strong></p>
            @else
                <p class="mb-4">Please fill your information below</p>
                <!-- Email -->
                <div class="my-4">
                    <label for="email" class="block text-sm font-medium text-red-700 dark:text-slate-100">Email</label>
                    <input type="email" name="email" id="email" x-model="email" class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm"/>
                </div>

                <!-- UserName -->
                <div class="my-4">
                    <label for="user_name" class="block text-sm font-medium text-slate-700 dark:text-slate-100">User Name</label>
                    <input type="text" name="user_name" id="user_name" x-model="userName" class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm"/>
                </div>

                <!-- Password -->
                <div class="my-4">
                    <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-100">Password</label>
                    <input type="password" name="password" id="password" x-model="password" class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm"/>
                </div>

                <div class="my-4">
                    <label for="confirm_password" class="block text-sm font-medium text-slate-700 dark:text-slate-100">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" x-model="confirmPassword" class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm"/>
                </div>
            @endauth

            <p class="mb-4">To make a payment, please paste in your Shopify order reference or ID below</p>

            <!-- Dropdown select to choose from fetch by order_id, order_name or order_confirmation_number -->
            {{-- <div class="my-4">
                <label for="fetch_order_by" class="block text-sm font-medium text-slate-700 dark:text-slate-100">Choose what to fetch the order by</label>
                <select name="fetch_order_by" id="fetch_order_by" x-model="fetchOrderBy" class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm">
                    <option selected value="confirmation_number">Order Confirmation Number</option>
                    <option value="order_id">Order ID</option>
                    <option value="name">Order Name</option>
                </select>
            </div> --}}

            <!-- Order ID -->
            <div class="my-4">
                <label for="confirmation_number" class="block text-sm font-medium text-slate-700 dark:text-slate-100">Order Confirmation Number</label>
                <input type="text" name="confirmation_number" id="confirmation_number" x-model="confirmationNumber" class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm">
            </div>

            <!-- Button and Payment Info -->
            <div class="mt-4">
                <button
                    @click="getOrderInfo"
                    :disabled="loading || !confirmationNumber"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    :class="loading || !confirmationNumber ? 'cursor-not-allowed bg-slate-700 hover:bg-slate-900' : 'cursor-pointer'"
                    x-text="loading ? 'Fetching...' : 'Get Payment Information'">
                </button>
            </div>

            <!-- Display Error -->
            <div x-show="errorMessage" class="mt-4 text-red-500">
                <p><strong>Error:</strong> <span x-text="errorMessage || 'N/A'"></span></p>
            </div>

            <!-- Display Order Information -->
            <div class="mt-4" x-show="orderFetched">
                <h2 class="text-lg font-bold mb-2">Order Information</h2>
                <p><strong>Payment Status:</strong> <span x-text="orderData.displayFinancialStatus || 'N/A'"></span></p>
                <p><strong>Order ID:</strong>
                    <span x-text="orderData.id || 'N/A'"></span>
                </p>
                <p><strong>Email:</strong>
                    <span x-text="orderData.email || 'N/A'"></span>
                </p>
                <p><strong>Total Price:</strong>
                    <span x-text="orderData.currentTotalPriceSet?.shopMoney?.amount || 'N/A'"></span>
                    <span x-text="orderData.currentTotalPriceSet?.shopMoney?.currencyCode || ''"></span>
                </p>
                <p><strong>Transaction Status:</strong>
                    <span x-text="orderData.transactions?.[0]?.status || 'N/A'"></span>
                </p>
                <button
                    @click="proceedToPayment"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-4">
                    Proceed to Payment
                </button>
            </div>
        </div>

        <!-- Alpine.js Script -->
        <script>
            function paymentForm() {
                return {
                    isUserLoggedIn: {{ auth()->check() ? 'true' : 'false' }},
                    email: '',
                    userName: '',
                    password: '',
                    confirmPassword: '',
                    confirmationNumber: '',
                    orderData: {},
                    loading: false,
                    errorMessage: '',
                    orderFetched: false,
                    validatePassword() {
                        if (this.password !== this.confirmPassword) {
                            this.errorMessage = 'Passwords do not match';
                        } else {
                            this.errorMessage = '';
                        }
                    },
                    getOrderInfo() {
                        if ( !this.confirmationNumber) {
                            this.errorMessage = 'Please provide the confirmation number';
                            return;
                        }

                        if (!this.isUserLoggedIn) {
                            if (!this.email || !this.userName || !this.password || !this.confirmPassword) {
                                this.errorMessage = 'Please fill all required fields (email, username, password, confirm password).';
                                return false;
                            }

                            if (this.password !== this.confirmPassword) {
                                this.errorMessage = 'Passwords do not match.';
                                return false;
                            }
                        }

                        this.loading = true;
                        this.errorMessage = '';
                        this.orderFetched = false;

                        // TODO register finApi user and get token

                        switch (this.fetchOrderBy) {
                            case 'order_id':
                                url = `/api/v1/shopify/order/get-order-by-id?order_id=${this.confirmationNumber}`;
                                break;

                            case 'name':
                                url = `/api/v1/shopify/order/get-order-by-name?name=${this.confirmationNumber}`;
                                break;

                            case 'confirmation_number':
                                url = `/api/v1/shopify/order/get-order-by-confirmation-number?confirmation_number=${this.confirmationNumber}`;
                                break;

                            default:
                                url = `/api/v1/shopify/order/get-order-by-confirmation-number?confirmation_number=${this.confirmationNumber}`;
                                break;
                        }

                        fetch(url, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.id) {
                                this.orderData = data;
                                this.orderFetched = true;
                            } else {
                                this.errorMessage = 'Order not found.';
                            }
                        })
                        .catch(() => {
                            this.errorMessage = 'Failed to fetch order information. Please try again.';
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                    },
                    proceedToPayment() {
                        const amount = this.orderData.currentTotalPriceSet?.shopMoney?.amount;
                        const currency = this.orderData.currentTotalPriceSet?.shopMoney?.currencyCode;
                        const email = this.email;
                        const password = this.password;
                        const confirmationNumber = this.confirmationNumber;

                        if (!amount || !currency) {
                            this.errorMessage = 'Unable to proceed, missing amount or currency.';
                            return;
                        }

                        fetch('/shopify/payment/redirect-to-payment-form', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ amount, currency, email, password, confirmationNumber })
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
