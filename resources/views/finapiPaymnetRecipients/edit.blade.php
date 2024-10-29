<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Edit FinAPI Payment Recipient') }}
        </h2>
    </x-slot>
    <x-slot name="slot">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-6 text-slate-900 dark:text-slate-100">
                <h1 class="text-2xl font-bold mb-6">Edit Recipient Information</h1>
                <form action="{{ route('finapi-payment-recipient.update', $recipient->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT') <!-- Method for updating resource -->

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium">Name</label>
                        <input type="text" name="name" id="name" class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm" value="{{ old('name', $recipient->name) }}" required>
                        @error('name')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- IBAN -->
                    <div>
                        <label for="iban" class="block text-sm font-medium">IBAN</label>
                        <input type="text" name="iban" id="iban" class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm" value="{{ old('iban', $recipient->iban) }}" required>
                        @error('iban')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- BIC (Optional) -->
                    <div>
                        <label for="bic" class="block text-sm font-medium">BIC (Optional)</label>
                        <input type="text" name="bic" id="bic" class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm" value="{{ old('bic', $recipient->bic) }}">
                        @error('bic')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bank Name (Optional) -->
                    <div>
                        <label for="bank_name" class="block text-sm font-medium">Bank Name (Optional)</label>
                        <input type="text" name="bank_name" id="bank_name" class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm" value="{{ old('bank_name', $recipient->bank_name) }}">
                        @error('bank_name')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address: Street (Optional) -->
                    <div>
                        <label for="street" class="block text-sm font-medium">Street (Optional)</label>
                        <input type="text" name="street" id="street" class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm" value="{{ old('street', $recipient->street) }}">
                        @error('street')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address: House Number (Optional) -->
                    <div>
                        <label for="house_number" class="block text-sm font-medium">House Number (Optional)</label>
                        <input type="text" name="house_number" id="house_number" class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm" value="{{ old('house_number', $recipient->house_number) }}">
                        @error('house_number')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address: Post Code (Optional) -->
                    <div>
                        <label for="post_code" class="block text-sm font-medium">Post Code (Optional)</label>
                        <input type="text" name="post_code" id="post_code" class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm" value="{{ old('post_code', $recipient->post_code) }}">
                        @error('post_code')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address: City (Optional) -->
                    <div>
                        <label for="city" class="block text-sm font-medium">City (Optional)</label>
                        <input type="text" name="city" id="city" class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm" value="{{ old('city', $recipient->city) }}">
                        @error('city')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Country (Optional) -->
                    <div>
                        <label for="country" class="block text-sm font-medium">Country (Optional)</label>
                        <input type="text" name="country" id="country" class="mt-1 block w-full border-slate-700 bg-slate-300 text-slate-900 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm" value="{{ old('country', $recipient->country) }}">
                        @error('country')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Update Recipient</button>
                    </div>
                </form>
            </div>
        </div>
    </x-slot>
</x-app-layout>
