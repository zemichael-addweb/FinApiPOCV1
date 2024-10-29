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

            <!-- notice for B2B customers to login -->
            <div x-show="finapiUser && finapiUser.id" class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 my-4" role="alert">
                <p class="font-bold">Notice</p>
                <p class="text-sm">B2B account with the email provided exists. Please login to use deposit.</p>
                <a href="{{ route('login') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded mt-4 inline-block">Login</a>
            </div>

            <!-- Tab Navigation -->
            <div class="flex border-b border-slate-300 dark:border-slate-700 mb-4">
                <button
                    :class="activeTab === 'confirmation' ? 'border-b-2 border-blue-500 text-blue-500' : ''"
                    @click="activeTab = 'confirmation'"
                    class="py-2 px-4 text-slate-700 dark:text-slate-100 font-semibold focus:outline-none"
                >
                    Pay with Confirmation Number
                </button>
                <button
                    :class="activeTab === 'manual' ? 'border-b-2 border-blue-500 text-blue-500' : ''"
                    @click="activeTab = 'manual'"
                    class="py-2 px-4 text-slate-700 dark:text-slate-100 font-semibold focus:outline-none"
                >
                    Pay without Confirmation Number
                </button>
                <button
                    x-show="isUserLoggedIn"
                    :class="activeTab === 'deposit' ? 'border-b-2 border-blue-500 text-blue-500' : ''"
                    @click="activeTab = 'deposit'"
                    class="py-2 px-4 text-slate-700 dark:text-slate-100 font-semibold focus:outline-none"
                >
                    Pay from deposit
                </button>
            </div>

            <!-- User Information -->
            <div class="container mx-auto">
                @auth
                    <p class="mb-4">You are logged in as <strong>{{ auth()->user()->name }}</strong></p>
                    <p x-show="email" class="mb-4">Email : <strong x-text="email"></strong></p>
                @else
                    <p class="mb-4">Please fill your information below</p>
                    <!-- Email -->
                    <div class="my-4">
                        <label for="email" class="block text-sm font-medium text-red-700 dark:text-slate-100">Email</label>
                        <input @keyup="getFinapiUserInformation" type="email" name="email" id="email" x-model="email" class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm"/>
                    </div>

                    <!-- UserName -->
                    <div class="my-4">
                        <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-100">Name</label>
                        <input type="text" name="name" id="name" x-model="name" class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm"/>
                    </div>
                @endauth
            </div>

            <!-- Pay with Confirmation Number -->
            <div x-show="activeTab === 'confirmation'">
                <h2 class="text-lg font-bold mb-2">Manual Payment with Shopify Confirmation Number</h2>
                <p class="mb-4">Enter the your Shopify order reference (Confirmation Number) to proceed.</p>

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

            <!-- Pay without Confirmation Number -->
            <div x-show="activeTab === 'manual'">
                <h2 class="text-lg font-bold mb-2">Manual Payment</h2>
                <p class="mb-4">Enter the payment amount to proceed.</p>

                <!-- Amount Input -->
                <div class="my-4">
                    <label for="amount" class="block text-sm font-medium text-slate-700 dark:text-slate-100">Amount</label>
                    <input type="text" name="amount" id="amount" x-model="manualAmount" class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm">
                </div>
                <!-- Currency Input -->
                <div class="my-4">
                    <label for="currency" class="block text-sm font-medium text-slate-700 dark:text-slate-100">Amount</label>
                    <input readonly type="text" name="currency" id="currency" x-model="manualCurrency" class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm">
                </div>

                <div class="mt-4">
                    <button
                        @click="proceedWithManualPayment"
                        :disabled="manualLoading || !manualAmount || !manualCurrency || !email"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                        :class="manualLoading || !manualAmount ? 'cursor-not-allowed bg-slate-700 hover:bg-slate-900' : 'cursor-pointer'"
                    >
                        Proceed to Payment
                    </button>
                </div>

                <!-- Display Error -->
                <div x-show="errorMessage" class="mt-4 text-red-500">
                    <p><strong>Error:</strong> <span x-text="errorMessage || 'N/A'"></span></p>
                </div>
            </div>

            <!-- Pay from Deposit -->
            <div x-show="activeTab === 'deposit'">
                <h2 class="text-lg font-bold mb-2">Pay with deposit</h2>
                <p class="mb-4">Enter the payment amount to proceed.</p>

                <!-- total deposit amount -->
                <div class="my-4">
                    <span class="block font-bold text-slate-700 dark:text-slate-100">Total Deposit Amount: <span x-text="totalDeposit"></span></span>
                </div>

                <!-- Amount Input -->
                <div class="my-4">
                    <label for="amount" class="block text-sm font-medium text-slate-700 dark:text-slate-100">Amount</label>
                    <input type="text" name="deposit-amount" id="deposit-amount" x-model="depositAmount" class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm">
                </div>
                <!-- Currency Input -->
                <div class="my-4">
                    <label for="currency" class="block text-sm font-medium text-slate-700 dark:text-slate-100">Amount</label>
                    <input readonly type="text" name="deposit-currency" id="deposit-currency" x-model="depositCurrency" class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm">
                </div>

                <div class="mt-4">
                    <button
                        @click="proceedWithDepositPayment"
                        :disabled="depositLoading || !depositAmount || !depositCurrency || !email"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                        :class="depositLoading || !depositAmount ? 'cursor-not-allowed bg-slate-700 hover:bg-slate-900' : 'cursor-pointer'"
                    >
                        Proceed to Payment
                    </button>
                </div>

                <!-- Display Error -->
                <div x-show="errorMessage" class="mt-4 text-red-500">
                    <p><strong>Error:</strong> <span x-text="errorMessage || 'N/A'"></span></p>
                </div>
            </div>
        </div>

        <script>
            function paymentForm() {
                return {
                    activeTab: 'confirmation',
                    totalDeposit : 0,
                    depositAmount : 0,
                    depositCurrency : 'EUR',
                    depositLoading : false,
                    manualAmount: '',
                    manualCurrency: 'EUR',
                    manualLoading: false,
                    isUserLoggedIn: {{ auth()->check() ? 'true' : 'false' }},
                    finapiUser: {},
                    email: '{{ auth()->check() ? auth()->user()->email : '' }}',
                    name: '',
                    confirmationNumber: '',
                    orderData: {},
                    loading: false,
                    errorMessage: '',
                    orderFetched: false,
                    getFinapiUserInformation() {
                        this.finapiUser={}
                        if ( !this.email || this.isUserLoggedIn) {
                            return;
                        }

                        this.errorMessage = '';

                        fetch(`{{ route("users.get-finapi-user") }}?email=${this.email}`, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log(data)
                            if (data.error) {
                                this.errorMessage = data.error;
                                return;
                            }

                            this.finapiUser = data.finapiUser;
                        })
                        .catch(() => {
                            console.error('Failed to fetch user information. Please try again.');
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                    },
                    getOrderInfo() {
                        if ( !this.confirmationNumber) {
                            this.errorMessage = 'Please provide the confirmation number';
                            return;
                        }

                        if (!this.isUserLoggedIn) {
                            if (!this.email || !this.name) {
                                this.errorMessage = 'Please fill all required fields (email, name).';
                                return false;
                            }
                        }

                        this.loading = true;
                        this.errorMessage = '';
                        this.orderFetched = false;

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

                        showLoading();

                        fetch(url, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.data && data.data.id) {
                                if(data.data.email != this.email){
                                  this.errorMessage = `The email in the order information [${data.data.email}] may not match provided email [${this.email}].`
                                }
                                this.orderData = data.data;
                                this.orderFetched = true;
                            } else {
                                this.errorMessage = 'Order not found.';
                            }
                        })
                        .catch(() => {
                            this.errorMessage = 'Failed to fetch order information. Please try again.';
                        })
                        .finally(() => {
                            hideLoading();
                            this.loading = false;
                        });
                    },
                    proceedToPayment() {
                        const amount = this.orderData.currentTotalPriceSet?.shopMoney?.amount;
                        const currency = this.orderData.currentTotalPriceSet?.shopMoney?.currencyCode;

                        if (!amount || !currency) {
                            this.errorMessage = 'Unable to proceed, missing amount or currency.';
                            return;
                        }

                        // ! check if we need to take the other email

                        this.getPaymentFormAndRedirect(amount, currency, this.email, this.confirmationNumber);
                    },
                    proceedWithManualPayment() {
                        if (!this.manualAmount || !this.manualCurrency) {
                            this.errorMessage = 'Unable to proceed, missing amount or currency.';
                            return;
                        }

                        if (!this.isUserLoggedIn) {
                            if (!this.email || !this.name) {
                                this.errorMessage = 'Please fill all required fields (email, username, password, confirm password).';
                                return false;
                            }
                        }

                        this.manualLoading = true;
                        this.errorMessage = '';

                        this.getPaymentFormAndRedirect(this.manualAmount, this.manualCurrency, this.email, this.confirmationNumber);
                    },
                    proceedWithDepositPayment() {
                        if (!this.depositAmount || !this.depositCurrency) {
                            this.errorMessage = 'Unable to proceed, missing amount or currency.';
                            return;
                        }

                        if (!this.isUserLoggedIn) {
                            if (!this.email || !this.name) {
                                this.errorMessage = 'Please fill all required fields (email, name).';
                                return false;
                            }
                        }

                        this.depositLoading = true;
                        this.errorMessage = '';

                        showLoading();

                        fetch(`{{ route('deposit.pay-from-deposit') }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ amount:this.depositAmount, currency:this.depositCurrency, email:this.email })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.url) {
                                window.location.href = data.url; // Redirect to payment form
                            } else {
                                this.errorMessage = 'Failed to initiate payment. Please try again.';
                            }
                            hideLoading();
                        })
                        .catch(() => {
                            this.errorMessage = 'Failed to initiate payment. Please try again.';
                            hideLoading();
                        });

                        console.log(this.depositAmount, this.depositCurrency, this.email, this.confirmationNumber);

                        this.depositLoading = false;
                    },
                    getPaymentFormAndRedirect(amount, currency, email, confirmationNumber = null) {
                      showLoading();

                      fetch('/shopify/payment/redirect-to-payment-form', {
                          method: 'POST',
                          headers: {
                              'Content-Type': 'application/json',
                              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                          },
                          body: JSON.stringify({ amount, currency, email, confirmationNumber })
                      })
                      .then(response => response.json())
                      .then(data => {
                          if (data.url) {
                              window.location.href = data.url; // Redirect to payment form
                          } else {
                              this.errorMessage = 'Failed to initiate payment. Please try again.';
                          }
                          hideLoading();
                      })
                      .catch(() => {
                          this.errorMessage = 'Failed to initiate payment. Please try again.';
                          hideLoading();
                      });
                      this.loading = false;
                      this.manualLoading = false;
                    },
                }
            }
        </script>
    </x-slot>
</x-app-layout>
