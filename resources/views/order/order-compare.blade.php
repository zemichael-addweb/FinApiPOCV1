<x-app-layout>
    <x-slot name="header">
        {{ __('Comparison') }}
    </x-slot>

    <x-slot name="slot">
        <div class="text-slate-800 dark:text-slate-200 m-4 p-4">
            <h2 class="text-lg font-bold">Order to Payment Comparison</h2>
        </div>
        <hr class="mb-4">

        <div x-data="comparisonData()" class="p-4">
            <div class="flex items-center gap-2 mb-2">
                <label for="filterCriteria" class="text-sm font-semibold">Match By:</label>
                <select id="filterCriteria" x-model="filterCriteria" class="border border-gray-300 rounded p-1">
                    <option value="amount">Amount</option>
                    <option value="email">Email</option>
                </select>
                <button @click="applyFilter" class="px-3 py-1 bg-blue-500 text-white rounded">Filter</button>
            </div>
            <!-- Orders Column -->
            <div  class="grid grid-cols-2 gap-4 p-4">
                <div>
                    <h3 class="text-md font-semibold mb-2">Orders</h3>
                    <div class="overflow-y-auto border border-gray-300 rounded-md max-h-96">
                        <template x-for="order in orders" :key="order.node.id">
                            <div
                                @click="selectOrder(order)"
                                :class="{'bg-red-800-200': selectedOrder && selectedOrder.node.id === order.node.id, 'hover:bg-blue-800-100': true}"
                                class="cursor-pointer p-4 border-b border-gray-200"
                            >
                                <p class="font-bold" x-text="order.node.name"></p>
                                <p>Email: <span x-text="order.node.email"></span></p>
                                <p>Amount: <span x-text="order.node.currentTotalPriceSet.shopMoney.amount + ' ' + order.node.currentTotalPriceSet.shopMoney.currencyCode"></span></p>
                                <p>Status: <span x-text="order.node.displayFinancialStatus"></span></p>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Payments Column -->
                <div>
                    <h3 class="text-md font-semibold mb-2">Payments</h3>
                    <div class="overflow-y-auto border border-gray-300 rounded-md max-h-96">
                        <template x-if="selectedPayment">
                            <div class="p-4">
                                <p class="font-bold">Payment ID: <span x-text="selectedPayment.payment_id"></span></p>
                                <p>Amount: <span x-text="selectedPayment.amount + ' EUR'"></span></p>
                                <p>Status: <span x-text="selectedPayment.status_v2"></span></p>
                                <p>IBAN: <span x-text="selectedPayment.iban"></span></p>
                                <p>Email: <span x-text="selectedPayment.finapi_user.email || 'N/A'"></span></p>
                                <p>Execution Date: <span x-text="selectedPayment.execution_date || 'N/A'"></span></p>
                                <p>Entry Create At: <span x-text="selectedPayment.created_at || 'N/A'"></span></p>
                                <p><a :href="selectedPayment.form.form_url" target="_blank" class="text-blue-500 underline">Payment Form</a></p>
                                <button @click="matchOrderAndPayment" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Match</button>
                            </div>
                        </template>
                        <template x-if="!selectedPayment">
                            <p class="text-gray-500 text-center mt-4">Select an order to see matching payment details.</p>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <script>
          function comparisonData() {
              return {
                  orders: [],
                  payments: [],
                  selectedOrder: null,
                  selectedPayment: null,
                  filterCriteria: 'amount',  // default filter is by amount

                  async fetchOrders() {
                      const response = await fetch('{{ route('admin.order.get-orders') }}');
                      const data = await response.json();
                      this.orders = data.data;
                  },

                  async fetchPayments() {
                      const response = await fetch('{{ route('admin.payment.getpayments') }}');
                      const data = await response.json();
                      this.payments = data;
                  },

                  selectOrder(order) {
                      this.selectedOrder = order;
                      this.selectedPayment = this.findClosestPayment(order);
                  },

                  findClosestPayment(order) {
                      if (this.filterCriteria === 'amount') {
                          return this.payments.find(payment =>
                              parseFloat(payment.amount) === parseFloat(order.node.currentTotalPriceSet.shopMoney.amount)
                          ) || null;
                      } else if (this.filterCriteria === 'email') {
                          return this.payments.find(payment =>
                              payment.finapi_user?.email === order.node.email
                          ) || null;
                      }
                      return null;
                  },

                  applyFilter() {
                      if (this.selectedOrder) {
                          this.selectedPayment = this.findClosestPayment(this.selectedOrder);
                      }
                  },

                  matchOrderAndPayment() {
                      if (this.selectedOrder && this.selectedPayment) {
                          console.log("Matched Order ID:", this.selectedOrder.node.id);
                          console.log("Matched Payment ID:", this.selectedPayment.payment_id);
                      } else {
                          console.log("Please select both an order and a matching payment.");
                      }
                  },

                  async init() {
                      await this.fetchOrders();
                      await this.fetchPayments();
                  }
              }
          }
        </script>
    </x-slot>
</x-app-layout>
