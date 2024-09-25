
<x-app-layout>
    <x-slot name="header">
        {{ __('Order') }}
    </x-slot>

    <x-slot name="slot">
        <div class="flex lg:col-start-2 text-gray-800 dark:text-gray-200 m-4 p-4">
            Orders Table
        </div>
        <hr>
    </x-slot>
</x-app-layout>