<x-app-layout>
    <x-slot name="slot">
        <div class="py-12">
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <h1 class="text-title-md2 font-bold text-black dark:text-white">
                    Set Up Two-Factor Authentication
                </h1>
            </div>
            <div class="w-full" x-data="setupTwoFactor()">
                <form class="max-w-sm"  @submit.prevent="submitForm">
                    @csrf
                    <div class="bg-white shadow-lg rounded-lg p-8 max-w-md w-full">
                        <p class="text-gray-600 mb-6">Enhance your account security by enabling Google 2FA. Scan the QR code below with your authenticator app, and enter the code to verify setup.</p>

                        @if($verified)
                            <p class="text-green-700 mb-6">2FA is Enabled!</p>
                        @else
                            <div class="flex justify-center items-center mb-6">
                                <div class="border-2 border-dashed border-gray-300 rounded-lg w-32 h-32 flex items-center justify-center">
                        <span class="text-gray-400">
                            <img src="{!! $qr !!}" />

                        </span>
                                </div>
                            </div>

                            <label for="2fa-code" class="block text-sm font-medium text-gray-700">Enter 2FA Code</label>
                            <input type="text" id="2fa-code" name="2fa-code" placeholder="123456"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm mb-4" x-model="setupCode" required>

                            <div class="flex justify-end space-x-4">
                                <button type="button"
                                        @click="verifyTwoFactor"
                                        :disabled="loading"
                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                        :class="loading ? 'cursor-not-allowed bg-slate-700 hover:bg-slate-900' : 'cursor-pointer'"
                                        x-text="loading ? 'Processing...' : 'Verify'">
                                </button>
                            </div>
                        @endif

                    </div>
                </form>

            </div>
        </div>

        <script>
            function setupTwoFactor() {
                return {
                    setupCode: '',
                    errorMessage: '',
                    loading: false,

                    async verifyTwoFactor() {
                        this.loading = true;
                        this.errorMessage = '';

                        fetch(`{{ route ('verify-2fa') }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                code: this.setupCode
                            })
                        })
                            .then(response => response.json())
                            .then(data => {
                                this.loading = false;
                                console.log(data);
                                // if (data.url) {
                                //     window.location.href = data.url;
                                // } else if (data.error) {
                                //     this.errorMessage = `Error: ${data.error}`;
                                // }
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
