
<x-guest-layout>

        <x-slot name="slot">
            <div class="flex lg:col-start-2 text-slate-800 dark:text-slate-200 m-4 p-4">
                Welcome!
            </div>
            <hr>
            <div class=" container mx-auto p-4 d-none" x-data="paymentForm()">
                @if (Route::has('login'))
                    <div class="-mx-3 flex flex-1 m-3 p-3">
                        <nav class="-mx-3 flex flex-1 gap-4 justify-start m-3">

                            @auth
                            @else


                            <a href="{{ url('/payments/create') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Make Payment</a>

                            <a href="{{ url('login') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Login</a>


                            <!-- <a
                                href="{{ url('/payments/create') }}"
                                class="rounded-md px-3 py-2 text-black ring-1 border-2 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Make Payment
                            </a>
                            <a href="{{ route('login') }}"
                                class="rounded-md px-3 py-2 text-black ring-1 border-2 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                                Log in
                            </a> -->
                            @endauth
                        </nav>
                    </div>
                @endif
            </div>
        </x-slot>
</x-guest-layout>
