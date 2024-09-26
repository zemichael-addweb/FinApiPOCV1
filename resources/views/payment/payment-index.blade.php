
<x-app-layout>
    <x-slot name="header">
        
        <div class="flex flex-grow">
            <span class="">{{ __('Payment') }}</span>
            <a class="ms-auto" href="{{route('payments.create')}}">Make Payment</a>
        </div>
    </x-slot>

    <x-slot name="slot">
        <div class="flex lg:col-start-2 text-gray-800 dark:text-gray-200 m-4 p-4">
            Payment Table
        </div>
        <hr>
    </x-slot>
</x-app-layout>
