
<x-guest-layout>
        <x-slot name="header">
            <div class="flex flex-grow">
                <span class="">{{ __('Welcome') }}</span>
                <a class="ms-auto" href="{{route('payments.create')}}">Make Payment</a>
            </div>
        </x-slot>

        <x-slot name="slot">
            <div class="flex lg:col-start-2 text-slate-800 dark:text-slate-200 m-4 p-4">
                Welcome!
            </div>
            <hr>
            <div class="container mx-auto p-4" x-data="paymentForm()">
                <h1 class="text-2xl font-bold mb-4">Make a payment</h1>
                @if (Route::has('login'))
                    <div class="-mx-3 flex flex-1 m-3 p-3">
                        <nav class="-mx-3 flex flex-1 gap-4 justify-start m-3">
                            <a
                                href="{{ url('/payments/create') }}"
                                class="rounded-md px-3 py-2 text-black ring-1 border-2 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Make Payment
                            </a>
                            @auth
                            @else
                            <a href="{{ route('login') }}"
                                class="rounded-md px-3 py-2 text-black ring-1 border-2 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                                Log in
                            </a>
                            @endauth
                        </nav>
                    </div>
                @endif
            </div>
        </x-slot>
</x-guest-layout>
