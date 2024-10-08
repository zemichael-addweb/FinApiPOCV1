<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add FinAPI Payment Recipient') }}
        </h2>
    </x-slot>
    <x-slot name="slot">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="max-w-2xl mx-auto py-12">
                            <h1 class="text-2xl font-bold mb-6">Add Recipient Information</h1>
                        
                            <form action="{{ route('finapi-payment-recipient.store') }}" method="POST" class="space-y-6">
                                @csrf
                                <!-- Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('name') }}" required>
                                    @error('name')
                                        <p class="text-red-600 text-sm">{{ $message }}</p>
                                    @enderror
                                </div>
                        
                                <!-- IBAN -->
                                <div>
                                    <label for="iban" class="block text-sm font-medium text-gray-700">IBAN</label>
                                    <input type="text" name="iban" id="iban" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('iban') }}" required>
                                    @error('iban')
                                        <p class="text-red-600 text-sm">{{ $message }}</p>
                                    @enderror
                                </div>
                        
                                <!-- BIC (Optional) -->
                                <div>
                                    <label for="bic" class="block text-sm font-medium text-gray-700">BIC (Optional)</label>
                                    <input type="text" name="bic" id="bic" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('bic') }}">
                                    @error('bic')
                                        <p class="text-red-600 text-sm">{{ $message }}</p>
                                    @enderror
                                </div>
                        
                                <!-- Bank Name (Optional) -->
                                <div>
                                    <label for="bank_name" class="block text-sm font-medium text-gray-700">Bank Name (Optional)</label>
                                    <input type="text" name="bank_name" id="bank_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('bank_name') }}">
                                    @error('bank_name')
                                        <p class="text-red-600 text-sm">{{ $message }}</p>
                                    @enderror
                                </div>
                        
                                <!-- Address: Street (Optional) -->
                                <div>
                                    <label for="street" class="block text-sm font-medium text-gray-700">Street (Optional)</label>
                                    <input type="text" name="street" id="street" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('street') }}">
                                    @error('street')
                                        <p class="text-red-600 text-sm">{{ $message }}</p>
                                    @enderror
                                </div>
                        
                                <!-- Address: House Number (Optional) -->
                                <div>
                                    <label for="house_number" class="block text-sm font-medium text-gray-700">House Number (Optional)</label>
                                    <input type="text" name="house_number" id="house_number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('house_number') }}">
                                    @error('house_number')
                                        <p class="text-red-600 text-sm">{{ $message }}</p>
                                    @enderror
                                </div>
                        
                                <!-- Address: Post Code (Optional) -->
                                <div>
                                    <label for="post_code" class="block text-sm font-medium text-gray-700">Post Code (Optional)</label>
                                    <input type="text" name="post_code" id="post_code" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('post_code') }}">
                                    @error('post_code')
                                        <p class="text-red-600 text-sm">{{ $message }}</p>
                                    @enderror
                                </div>
                        
                                <!-- Address: City (Optional) -->
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700">City (Optional)</label>
                                    <input type="text" name="city" id="city" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('city') }}">
                                    @error('city')
                                        <p class="text-red-600 text-sm">{{ $message }}</p>
                                    @enderror
                                </div>
                        
                                <!-- Country (Optional) -->
                                <div>
                                    <label for="country" class="block text-sm font-medium text-gray-700">Country (Optional)</label>
                                    <input type="text" name="country" id="country" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('country') }}">
                                    @error('country')
                                        <p class="text-red-600 text-sm">{{ $message }}</p>
                                    @enderror
                                </div>
                        
                                <!-- Submit Button -->
                                <div>
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Save Recipient</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </x-slot>
</x-app-layout>