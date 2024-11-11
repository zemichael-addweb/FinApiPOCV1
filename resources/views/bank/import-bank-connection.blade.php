<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            <!-- back button to go back to /bank -->
            <a
                href="{{ route('bank.index') }}"
                class="rounded-md px-3 py-2 border text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
            > <i class="fa-solid fa-circle-left mx-2"></i>
                Back to Banks
            </a>
            <span class="mx-4 float-right">
                {{ __('Create Bank Connection') }}
            </span>
        </h2>
    </x-slot>

    <x-slot name="slot">
        <div class="flex lg:col-start-2 text-slate-800 dark:text-slate-200 m-4 p-4">
            Bank Connection
        </div>
        <hr>

        <div class="container mx-auto p-4" x-data="bankConnectionForm()">
            <h1 class="text-2xl font-bold mb-4">Create a Bank Connection</h1>
            @auth
                <p class="mb-4">You are logged in as <strong>{{ auth()->user()->name }}</strong></p>
            @endauth
            <p class="mb-4">Please fill in the details below</p>

            <form @submit.prevent="submitForm">
                @csrf
                <div class="mb-4">
                    <label for="bank_connection_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bank Connection Name</label>
                    <input type="text" id="bank_connection_name" name="bank_connection_name" x-model="bank_connection_name" required class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm">
                </div>

                <div class="mb-4">
                    <label for="max_days_for_download" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Days for Download</label>
                    <input type="number" id="max_days_for_download" name="max_days_for_download" x-model="max_days_for_download" class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm">
                </div>

                <div class="mb-4">
                    <label for="allowed_interfaces" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Allowed Interfaces</label>
                    <select id="allowed_interfaces" name="allowed_interfaces[]" x-model="allowed_interfaces" multiple class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm">
                        <option value="XS2A">XS2A</option>
                        <option value="FINTS_SERVER">FINTS_SERVER</option>
                        <option value="WEB_SCRAPER">WEB_SCRAPER</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="allow_test_bank" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Allow Test Bank</label>
                    <input id="allow_test_bank" name="allow_test_bank" x-model="allow_test_bank" type="checkbox" class="ml-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Yes</span>
                </div>

                <div x-show="errorMessage" class="mb-4 text-red-600">
                    <p x-text="errorMessage"></p>
                </div>

                <div class="mb-4">
                    <button type="button"
                            @click="proceedToCreateBankConnection"
                            :disabled="loading"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                            :class="loading ? 'cursor-not-allowed bg-slate-700 hover:bg-slate-900' : 'cursor-pointer'"
                            x-text="loading ? 'Processing...' : 'Create Bank Connection'">
                    </button>
                </div>
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
