<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Bank Information and Transactions') }}
        </h2>
    </x-slot>

    <x-slot name="slot">
        <hr>
        <div class="-mx-3 flex flex-1 m-3 p-3">
            <nav class="-mx-3 flex flex-1 justify-start m-3">
                <a
                    href="{{ route('admin.bank.import-bank-connection') }}"
                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    Import Bank Connection
                </a>
            </nav>
        </div>
        <hr>
        <div class="-mx-3 flex flex-1 m-3 p-3">
            <div class="w-full">
                <span> NO BANK INFORMATION !</span>
            </div>
        </div>

    </x-slot>
</x-app-layout>
