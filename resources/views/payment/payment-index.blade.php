<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Make Payment') }}
        </h2>
    </x-slot>

    <x-slot name="slot">
        <div class="-mx-3 flex flex-1 m-3 p-3">
            <nav class="-mx-3 flex flex-1 justify-start m-3">
                <a
                    href="{{ url('/payments/create') }}"
                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    Make Payment
                </a>

                <a
                    href="{{ url('/deposits/create') }}"
                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    Make Deposit
                </a>
            </nav>
        </div>
        <hr>
        <div class="flex lg:col-start-2 text-slate-800 dark:text-slate-200 m-4 p-4">
            Payments List
        </div>
        <span class="flex flex-1 justify-center items-center h-96 text-2xl text-slate-400 dark:text-slate-500">Coming Soon</span>
        <hr>
    </x-slot>
</x-app-layout>
