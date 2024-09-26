<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Make Payment') }}
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class=""bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-slate-900 dark:text-slate-100">
                    <form method="POST" action="/payments" x-data="{ instantPayment: false, structuredRemittanceInfo: '', counterpartAddress: { street: '', postCode: '', city: '', houseNumber: '', country: '' } }">
                        @csrf

                        <div x-data="{
                            moneyTransfers: [{
                                counterpart_name: '',
                                counterpart_iban: '',
                                counterpart_bic: '',
                                counterpart_bank_name: '',
                                amount: '',
                                currency: 'USD',
                                purpose: '',
                                sepa_purpose_code: '',
                                street: '',
                                post_code: '',
                                city: '',
                                house_number: '',
                                country: 'DE',
                                end_to_end_id: '',
                                structured_remittance_information: ''
                            }],
                            addMoneyTransfer() {
                                this.moneyTransfers.push({
                                    counterpart_name: '',
                                    counterpart_iban: '',
                                    counterpart_bic: '',
                                    counterpart_bank_name: '',
                                    amount: '',
                                    currency: 'USD',
                                    purpose: '',
                                    sepa_purpose_code: '',
                                    street: '',
                                    post_code: '',
                                    city: '',
                                    house_number: '',
                                    country: 'DE',
                                    end_to_end_id: '',
                                    structured_remittance_information: ''
                                });
                            },
                            removeMoneyTransfer(index) {
                                this.moneyTransfers.splice(index, 1);
                            }
                        }">
                            <!-- Account Details Section -->
                            <h3 class="text-lg font-bold mb-4">Account Details</h3>
                            <div class="border p-4 mb-4 relative bg-white dark:bg-slate-800 rounded-lg shadow-md">

                                <!-- Account ID -->
                                <x-input-field 
                                    name="account_id" 
                                    label="Account ID" 
                                    placeholder="Enter account ID" 
                                    required 
                                />
                        
                                <!-- IBAN -->
                                <x-input-field 
                                    name="iban" 
                                    label="IBAN" 
                                    placeholder="Enter IBAN" 
                                    required 
                                />
                        
                                <!-- Bank ID -->
                                <x-input-field 
                                    name="bank_id" 
                                    label="Bank ID" 
                                    placeholder="Enter bank ID" 
                                    required 
                                />
                        
                                <!-- Execution Date -->
                                <x-input-field 
                                    type="date" 
                                    name="execution_date" 
                                    label="Execution Date" 
                                    required 
                                />
                        
                                <!-- Instant Payment (Checkbox) -->
                                <div class="mb-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="instant_payment" class="mr-2">
                                        Instant Payment
                                    </label>
                                </div>
                        
                                <!-- Single Booking (Checkbox) -->
                                <div class="mb-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="single_booking" class="mr-2">
                                        Single Booking
                                    </label>
                                </div>
                        
                                <!-- Message ID -->
                                <x-input-field 
                                    name="msg_id" 
                                    label="Message ID" 
                                    placeholder="Enter message ID" 
                                />
                            </div>
                        
                            <!-- Money Transfer Section -->
                            <h3 class="text-lg font-bold mb-4">Money Transfer Details</h3>
                        
                            <div class="border p-4 mb-4 relative bg-white dark:bg-slate-800 rounded-lg shadow-md">
                            <!-- Loop through moneyTransfers array -->
                            <template x-for="(moneyTransfer, index) in moneyTransfers" :key="index">
                                <div class="border border-slate-500 p-4 mb-4 relative bg-white dark:bg-slate-800 rounded-lg shadow-md">
                                    <!-- Counterpart Name -->
                                    <x-input-field 
                                        :name="'counterpart_name[]'"
                                        label="Counterpart Name" 
                                        placeholder="Enter counterpart name" 
                                        required 
                                    />
                        
                                    <!-- Counterpart IBAN -->
                                    <x-input-field 
                                        :name="'counterpart_iban[]'"
                                        label="Counterpart IBAN" 
                                        placeholder="Enter counterpart IBAN" 
                                        required 
                                    />
                        
                                    <!-- Counterpart BIC -->
                                    <x-input-field 
                                        :name="'counterpart_bic[]'"
                                        label="Counterpart BIC" 
                                        placeholder="Enter counterpart BIC" 
                                        required 
                                    />
                        
                                    <!-- Counterpart Bank Name -->
                                    <x-input-field 
                                        :name="'counterpart_bank_name[]'"
                                        label="Counterpart Bank Name" 
                                        placeholder="Enter counterpart bank name" 
                                        required 
                                    />
                        
                                    <!-- Amount -->
                                    <x-input-field 
                                        type="number" 
                                        :name="'amount[]'"
                                        label="Amount" 
                                        placeholder="Enter amount" 
                                        required 
                                    />
                        
                                    <!-- Currency -->
                                    <x-input-field 
                                        :name="'currency[]'"
                                        label="Currency" 
                                        placeholder="Enter currency" 
                                        required 
                                        value="USD"
                                    />
                        
                                    <!-- Purpose -->
                                    <x-input-field 
                                        :name="'purpose[]'"
                                        label="Purpose" 
                                        placeholder="Enter purpose" 
                                        required 
                                    />
                        
                                    <!-- SEPA Purpose Code -->
                                    <x-input-field 
                                        :name="'sepa_purpose_code[]'"
                                        label="SEPA Purpose Code" 
                                        placeholder="Enter SEPA purpose code" 
                                        required 
                                    />
                        
                                    <!-- Counterpart Address: Street -->
                                    <x-input-field 
                                        :name="'counterpart_address[street][]'"
                                        label="Street" 
                                        placeholder="Enter street" 
                                        required 
                                    />
                        
                                    <!-- Counterpart Address: Post Code -->
                                    <x-input-field 
                                        :name="'counterpart_address[post_code][]'"
                                        label="Post Code" 
                                        placeholder="Enter post code" 
                                        required 
                                    />
                        
                                    <!-- Counterpart Address: City -->
                                    <x-input-field 
                                        :name="'counterpart_address[city][]'"
                                        label="City" 
                                        placeholder="Enter city" 
                                        required 
                                    />
                        
                                    <!-- Counterpart Address: House Number -->
                                    <x-input-field 
                                        :name="'counterpart_address[house_number][]'"
                                        label="House Number" 
                                        placeholder="Enter house number" 
                                        required 
                                    />
                        
                                    <!-- Counterpart Address: Country -->
                                    <x-input-field 
                                        :name="'counterpart_address[country][]'"
                                        label="Country" 
                                        placeholder="Enter country" 
                                        required 
                                        value="DE"
                                    />
                        
                                    <!-- End-to-End ID -->
                                    <x-input-field 
                                        :name="'end_to_end_id[]'"
                                        label="End-to-End ID" 
                                        placeholder="Enter end-to-end ID" 
                                    />
                        
                                    <!-- Structured Remittance Information -->
                                    <x-input-field 
                                        :name="'structured_remittance_information[]'"
                                        label="Structured Remittance Information" 
                                        placeholder="Enter structured remittance information" 
                                    />
                        
                                    <!-- Remove Button -->
                                    <button type="button" @click="removeMoneyTransfer(index)" class="absolute top-0 right-0 text-red-500 p-2">
                                        <i class="fas fa-minus-circle"></i> <!-- FontAwesome icon for minus -->
                                    </button>
                                </div>
                            </template>
                        
                            <!-- Add Button -->
                            <button type="button" @click="addMoneyTransfer" class="bg-green-500 text-white font-bold py-2 px-4 rounded my-4">
                                <i class="fas fa-plus-circle"></i> <!-- FontAwesome icon for plus -->
                                Add Money Transfer
                            </button>
                            </div>
                        </div>                        
                        
                        <!-- Submit Button -->
                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Submit Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
