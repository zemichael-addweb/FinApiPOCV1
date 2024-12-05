<x-app-layout>
    <x-slot name="slot">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-title-md2 font-bold text-black dark:text-white">
            Create Bank Connection
            </h1>

            <nav>
                <a href="{{ route('bank.index') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">View Bank Connection</a>
            </nav>
        </div>


        <div class="container mx-auto p-4" x-data="bankConnectionForm()">
            @auth
                <p class="mb-4">You are logged in as <strong>{{ auth()->user()->name }}</strong></p>
            @endauth
            <p class="mb-4">Please fill in the details below</p>



            <form class="max-w-sm"  @submit.prevent="submitForm">
                @csrf
                <div class="mb-5">
                    <label for="bank_connection_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bank Connection Name</label>
                    <input type="text" id="bank_connection_name" name="bank_connection_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  x-model="bank_connection_name" required />
                </div>
                <div class="mb-5">
                    <label for="max_days_for_download" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Max Days for Download</label>
                    <input type="number" id="max_days_for_download" name="max_days_for_download" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" x-model="max_days_for_download"  required />
                </div>
                <div class="mb-5">
                    <label for="allowed_interfaces" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Allowed Interfaces</label>
                    <select id="allowed_interfaces" name="allowed_interfaces[]" multiple class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" x-model="allowed_interfaces" required>
                        <option value="XS2A">XS2A</option>
                        <option value="FINTS_SERVER">FINTS_SERVER</option>
                        <option value="WEB_SCRAPER">WEB_SCRAPER</option>
                    </select>

                </div>
                <div class="flex items-start mb-5">
                    <div class="flex items-center h-5">
                    <input id="allow_test_bank" type="checkbox" value="" name="allow_test_bank" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800" x-model="allow_test_bank"  required />
                    </div>
                    <label for="allow_test_bank" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Allow Test Bank</label>
                </div>


                <button type="button"
                        @click="proceedToCreateBankConnection"
                        :disabled="loading"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        :class="loading ? 'cursor-not-allowed bg-slate-700 hover:bg-slate-900' : 'cursor-pointer'"
                        x-text="loading ? 'Processing...' : 'Create Bank Connection'">
                </button>
            </form>
        </div>

        <script>
            function bankConnectionForm() {
                return {
                    bank_connection_name: 'My Bank Connection',
                    max_days_for_download: 3650,
                    allowed_interfaces: ['XS2A', 'FINTS_SERVER', 'WEB_SCRAPER'],
                    allow_test_bank: true,
                    errorMessage: '',
                    loading: false,

                    async proceedToCreateBankConnection() {
                        this.loading = true;
                        this.errorMessage = '';

                        fetch(`{{ route ('admin.bank.redirect-to-import-bank-connection-form') }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                bank_connection_name: this.bank_connection_name,
                                max_days_for_download: this.max_days_for_download,
                                allowed_interfaces: this.allowed_interfaces,
                                allow_test_bank: this.allow_test_bank
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            this.loading = false;
                            if (data.url) {
                                window.location.href = data.url;
                            } else if (data.error) {
                                this.errorMessage = `Error: ${data.error}`;
                            }
                        })
                        .catch(error => {
                            this.loading = false;
                            this.errorMessage = `Failed to create bank connection: ${error.message}`;
                        });
                    }
                }
            }
        </script>
    </x-slot>
</x-app-layout>
