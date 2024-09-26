@php
    $isLoggedIn = Auth::check();
    $loggedInUser = $isLoggedIn ? Auth::user() : null;
    $userRole = $isLoggedIn ? $loggedInUser->role : null;
    $isAdmin = $isLoggedIn && $userRole === 'admin';
@endphp

<header class="sticky top-0 z-999 flex w-full bg-white drop-shadow-1 dark:bg-boxdark dark:drop-shadow-none">
    <div class="flex flex-grow items-center justify-between px-4 py-4 shadow-2 md:px-6 2xl:px-11">
        <div class="flex items-center gap-2 sm:gap-4 lg:hidden">
            <!-- Hamburger Toggle BTN -->
            <button
                class="z-99999 block rounded-sm border border-stroke bg-white p-1.5 shadow-sm dark:border-strokedark dark:bg-boxdark lg:hidden"
                @click.stop="sidebarToggle = !sidebarToggle">
                <span class="relative block h-5.5 w-5.5 cursor-pointer">
                    <span class="du-block absolute right-0 h-full w-full">
                        <span
                            class="relative left-0 top-0 my-1 block h-0.5 w-0 rounded-sm bg-black delay-[0] duration-200 ease-in-out dark:bg-white"
                            :class="{ '!w-full delay-300': !sidebarToggle }"></span>
                        <span
                            class="relative left-0 top-0 my-1 block h-0.5 w-0 rounded-sm bg-black delay-150 duration-200 ease-in-out dark:bg-white"
                            :class="{ '!w-full delay-400': !sidebarToggle }"></span>
                        <span
                            class="relative left-0 top-0 my-1 block h-0.5 w-0 rounded-sm bg-black delay-200 duration-200 ease-in-out dark:bg-white"
                            :class="{ '!w-full delay-500': !sidebarToggle }"></span>
                    </span>
                    <span class="du-block absolute right-0 h-full w-full rotate-45">
                        <span
                            class="absolute left-2.5 top-0 block h-full w-0.5 rounded-sm bg-black delay-300 duration-200 ease-in-out dark:bg-white"
                            :class="{ '!h-0 delay-[0]': !sidebarToggle }"></span>
                        <span
                            class="delay-400 absolute left-0 top-2.5 block h-0.5 w-full rounded-sm bg-black duration-200 ease-in-out dark:bg-white"
                            :class="{ '!h-0 dealy-200': !sidebarToggle }"></span>
                    </span>
                </span>
            </button>
            <!-- Hamburger Toggle BTN -->
            <a class="block flex-shrink-0 lg:hidden" href="#">
                <img width="176" height="32" src="{{ asset('images/logo/fin-API-Logo_RGB.png') }}" alt="Logo" />
            </a>
        </div>

        <div class="hidden sm:block">
            {{-- action --}}
        </div>

        <div class="flex items-center gap-3 2xsm:gap-7">
            <ul class="flex items-center gap-2 2xsm:gap-4">
                <li>
                    <!-- Dark Mode Toggler -->
                    <label :class="darkMode ? 'bg-primary' : 'bg-stroke'"
                        class="relative m-0 block h-7.5 w-14 rounded-full">
                        <input type="checkbox" :value="darkMode" @change="darkMode = !darkMode"
                            class="absolute top-0 z-50 m-0 h-full w-full cursor-pointer opacity-0" />
                        <span :class="darkMode && '!right-1 !translate-x-full'"
                            class="absolute left-1 top-1/2 flex h-6 w-6 -translate-y-1/2 translate-x-0 items-center justify-center rounded-full bg-white shadow-switcher duration-75 ease-linear">
                            <span class="dark:hidden">
                                <i class="fa-solid fa-sun"></i>
                            </span>
                            <span class="hidden dark:inline-block">
                                <i class="fa-solid fa-moon"></i>
                            </span>
                        </span>
                    </label>
                    <!-- Dark Mode Toggler -->
                </li>

                <!-- Notification Menu Area -->
                <li class="relative" x-data="{ dropdownOpen: false, notifying: true }" @click.outside="dropdownOpen = false">
                    <a class="relative flex h-8.5 w-8.5 items-center justify-center rounded-full border-[0.5px] border-stroke bg-gray hover:text-primary dark:border-strokedark dark:bg-meta-4 dark:text-white"
                        href="#" @click.prevent="dropdownOpen = ! dropdownOpen; notifying = false">
                        <span :class="!notifying && 'hidden'"
                            class="absolute -top-0.5 right-0 z-1 h-2 w-2 rounded-full bg-meta-1">
                            <span
                                class="absolute -z-1 inline-flex h-full w-full animate-ping rounded-full bg-meta-1 opacity-75"></span>
                        </span>

                        <i class="fa-regular fa-bell"></i>
                    </a>

                    <!-- Dropdown Start -->
                    <div x-show="dropdownOpen"
                        class="absolute -right-27 mt-2.5 flex h-90 w-75 flex-col rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark sm:right-0 sm:w-80">
                        <div class="px-4.5 py-3">
                            <h5 class="text-sm font-medium text-bodydark2">Notification</h5>
                        </div>

                        <ul class="flex h-auto flex-col overflow-y-auto">
                            <li>
                                <a class="flex flex-col gap-2.5 border-t border-stroke px-4.5 py-3 hover:bg-gray-2 dark:border-strokedark dark:hover:bg-meta-4"
                                    href="#">
                                    <p class="text-sm">
                                        <span class="text-black dark:text-white">Feature</span>
                                        Coming soon.
                                    </p>
                                    <p class="text-xs">Very Soon</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- Dropdown End -->
                </li>
                <!-- Notification Menu Area -->
            </ul>

            @if ($isLoggedIn)
                <!-- User Area -->
                <div class="relative" x-data="{ dropdownOpen: false }" @click.outside="dropdownOpen = false">
                    <a class="flex items-center gap-4" href="#" @click.prevent="dropdownOpen = ! dropdownOpen">
                        <span class="hidden text-right lg:block">
                            <span class="block text-sm font-medium text-black dark:text-white">
                                {{ Auth::user() ? Auth::user()->name : 'Guest User' }}
                            </span>
                            <span class="block text-xs font-medium">{{ Auth::user() ? Auth::user()->role : 'Guest' }}</span>
                        </span>

                        <img class="h-12 w-12 rounded-full" src="{{ asset('images/user/user.jpeg') }}" alt="User" />

                        <i class="fa-solid fa-chevron-up hidden fill-current sm:block" :class="dropdownOpen && 'rotate-180'" width="12" height="8"></i>
                    </a>

                    <!-- Dropdown Start -->
                    <div x-show="dropdownOpen"
                        class="absolute right-0 mt-4 flex w-62.5 flex-col rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
                        <ul class="flex flex-col gap-5 border-b border-stroke px-6 py-7.5 dark:border-strokedark">
                            <li>
                                <x-dropdown-link :href="route('profile.edit', Auth::user()->id)"
                                    class="flex items-center gap-3.5 text-sm font-medium duration-300 ease-in-out hover:text-primary lg:text-base">
                                    <i class="fa-regular fa-user"></i>
                                    {{ __('My Profile') }}
                                </x-dropdown-link>
                            </li>

                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link class="flex items-center gap-3.5 px-6 py-4 text-sm font-medium duration-300 ease-in-out hover:text-primary lg:text-base" 
                                        :href="route('logout')" 
                                        onclick="event.preventDefault();
                                        this.closest('form').submit();" >
                                        <i class="fa-solid fa-right-from-bracket"></i>
                                        {{ __('Log Out') }}
                                    </x-dropdown-link >
                                </form>
                            </li>
                        </ul>
                    </div>
                    <!-- Dropdown End -->
                </div>
                <!-- User Area -->
            @else
                @if (Route::has('login'))
                    <div class="-mx-3 ms-4 flex flex-1 gap-4">
                        <nav class="-mx-3 flex flex-1 justify-start m-3 align-middle">
                            <a href="{{ route('payments.create') }}"
                                class="rounded-md px-3 py-2 text-black  ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                                Make Payment
                            </a>
                        </nav>
                        <nav class="-mx-3 ms-4 flex flex-1 gap-4 justify-end m-3 border rounded-md border-slate-600 text-nowrap">
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                                    Log in
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                                        Register Business
                                    </a>
                                @endif
                            @endauth
                        </nav>
                    </div>
                @endif
            @endif
        </div>
    </div>
</header>
