<x-app-layout>
    <x-slot name="header">
        {{ __('Orders') }}
    </x-slot>

    <x-slot name="slot">
        <div class="flex lg:col-start-2 text-slate-800 dark:text-slate-200 m-4 p-4">
            <h2 class="text-lg font-bold">Orders Table</h2>
        </div>
        <hr>

        <div class="overflow-x-auto mt-4">
            @if($orders)
            <table class="min-w-full bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700">
                <thead>
                    <tr class="bg-slate-200 dark:bg-slate-700 text-nowrap">
                        <th class="py-2 px-4 text-left">Order ID</th>
                        <th class="py-2 px-4 text-left">Order Name</th>
                        <th class="py-2 px-4 text-left">Email</th>
                        <th class="py-2 px-4 text-left">Payment Gateway</th>
                        <th class="py-2 px-4 text-left">Total Received</th>
                        <th class="py-2 px-4 text-left">Total Outstanding</th>
                        <th class="py-2 px-4 text-left">Processed At</th>
                        <th class="py-2 px-4 text-left">Status</th>
                        <th class="py-2 px-4 text-left">Status Update</th>
                        <th class="py-2 px-4 text-left">Mark Paid</th>
                        <th class="py-2 px-4 text-left">Refund</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr class="border-t border-slate-300 dark:border-slate-700">
                            <td class="py-2 px-4">{{ $order->node->id }}</td>
                            <td class="py-2 px-4">{{ $order->node->name }}</td>
                            <td class="py-2 px-4">{{ $order->node->email }}</td>
                            <td class="py-2 px-4">{{ implode(', ', $order->node->paymentGatewayNames) }}</td>
                            <td class="py-2 px-4 text-nowrap">
                                {{ $order->node->currentTotalPriceSet->presentmentMoney->amount }}
                                {{ $order->node->currentTotalPriceSet->presentmentMoney->currencyCode }}
                            </td>
                            <td class="py-2 px-4">
                                {{ $order->node->totalOutstandingSet->presentmentMoney->amount }}
                                {{ $order->node->totalOutstandingSet->presentmentMoney->currencyCode }}
                            </td>
                            <td class="py-2 px-4">{{ $order->node->processedAt }}</td>
                            <td class="py-2 px-4">
                                {{ $order->node->displayFinancialStatus }}
                            </td>
                            <td class="py-2 px-4">
                                <!-- Payment Status Dropdown and Button -->
                                <div x-data="{ paymentStatus: '', orderId: '{{ $order->node->id }}', message: '' }">
                                    <div class="flex gap-2 p-2 border">
                                        <select x-model="paymentStatus" class="border-slate-300 dark:border-slate-600 rounded-md shadow-sm">
                                            <option value="">Select Status</option>
                                            <option value="pending">Pending</option>
                                            <option value="authorized">Authorized</option>
                                            <option value="partially_paid">Partially Paid</option>
                                            <option value="paid">Paid</option>
                                            <option value="partially_refunded">Partially Refunded</option>
                                            <option value="refunded">Refunded</option>
                                            <option value="voided">Voided</option>
                                        </select>
                                        <button @click="updatePaymentStatus" class="ml-2 text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded">
                                            Update
                                        </button>
                                    </div>

                                    <!-- Status Message -->
                                    <p x-text="message" class="text-sm mt-1" :class="message.includes('successfully') ? 'text-green-500' : 'text-red-500'"></p>

                                    <!-- AlpineJS Script for AJAX -->
                                    <script>
                                        function updatePaymentStatus() {
                                            if (!this.paymentStatus) {
                                                this.message = 'Please select a valid status.';
                                                return;
                                            }

                                            //fetch post request
                                            let token = document.head.querySelector('meta[name="csrf-token"]').content;
                                            fetch('{{ route('shopify.order.updateStatus') }}', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': token
                                                },
                                                body: JSON.stringify({
                                                    order_id: this.orderId,
                                                    payment_status: this.paymentStatus
                                                })
                                            }).then(response => {
                                                if (response.ok) {
                                                    this.message = 'Payment status updated successfully.';
                                                } else {
                                                    this.message = 'An error occurred.';
                                                }
                                            }).catch(error => {
                                                this.message = error.response.data.error || 'An error occurred.';
                                            });


                                            // axios.post('{{ route('shopify.order.updateStatus') }}', {
                                            //     order_id: this.orderId,
                                            //     payment_status: this.paymentStatus
                                            // })
                                            // .then(response => {
                                            //     console.log(response);
                                            //     this.message = 'Payment status updated successfully.';
                                            // })
                                            // .catch(error => {
                                            //     this.message = error.response.data.error || 'An error occurred.';
                                            // });
                                        }
                                    </script>
                                </div>
                            </td>
                            <td class="py-2 px-4 text-nowrap">
                                <!-- Payment Status Dropdown and Button -->
                                <div x-data="{ orderId: '{{ $order->node->id }}', message: '' }">
                                    <button @click="markOrderAsPaid" class="ml-2 text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded">
                                        Mark As Paid
                                    </button>

                                    <!-- Status Message -->
                                    <p x-text="message" class="text-sm mt-1" :class="message.includes('successfully') ? 'text-green-500' : 'text-red-500'"></p>

                                    <!-- AlpineJS Script for AJAX -->
                                    <script>
                                        function markOrderAsPaid() {
                                            //fetch post request
                                            let token = document.head.querySelector('meta[name="csrf-token"]').content;
                                            fetch('{{ route('shopify.order.markAsPaid') }}', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': token
                                                },
                                                body: JSON.stringify({
                                                    order_id: this.orderId,
                                                })
                                            }).then(response => {
                                                if (response.ok) {
                                                    this.message = 'Payment status updated successfully.';
                                                } else {
                                                    this.message = 'An error occurred.';
                                                }
                                            }).catch(error => {
                                                this.message = error.response.data.error || 'An error occurred.';
                                            });
                                        }
                                    </script>
                                </div>
                            </td>
                            <td class="py-2 px-4 text-nowrap">
                                <!-- Payment Status Dropdown and Button -->
                                <div x-data="{ orderId: '{{ $order->node->id }}', message: '' }">
                                    <button @click="refundOrder" class="ml-2 text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded">
                                        Refund
                                    </button>

                                    <!-- Status Message -->
                                    <p x-text="message" class="text-sm mt-1" :class="message.includes('successfully') ? 'text-green-500' : 'text-red-500'"></p>

                                    <!-- AlpineJS Script for AJAX -->
                                    <script>
                                        function refundOrder() {
                                            //fetch post request
                                            let token = document.head.querySelector('meta[name="csrf-token"]').content;
                                            fetch('{{ route('shopify.order.refund') }}', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': token
                                                },
                                                body: JSON.stringify({
                                                    order_id: this.orderId,
                                                })
                                            }).then(response => {
                                                if (response.ok) {
                                                    this.message = 'Payment status updated successfully.';
                                                } else {
                                                    this.message = 'An error occurred.';
                                                }
                                            }).catch(error => {
                                                this.message = error.response.data.error || 'An error occurred.';
                                            });
                                        }
                                    </script>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @else
                <tr>
                    <td class="py-2 px-4" colspan="5">
                        No orders found.
                    </td>
                </tr>
            @endif
        </div>
    </x-slot>
</x-app-layout>
