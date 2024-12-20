@php
    $isLoggedIn = Auth::check();
    $loggedInUser = $isLoggedIn ? Auth::user() : null;
    $userRole = $isLoggedIn ? $loggedInUser->role : null;
    $isAdmin = $isLoggedIn && $userRole === 'admin';
@endphp

<header class="sticky top-0 z-999 flex w-full bg-black drop-shadow-1 dark:bg-boxdark dark:drop-shadow-none">

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
                <img width="176" height="32" src="{{ asset('images/logo/terd_logo.png') }}" alt="Logo" />
            </a>
        </div>

        <div class="hidden sm:block">
            {{-- action --}}
        </div>

        <div class="flex items-center gap-3 2xsm:gap-7">

            @if ($isLoggedIn)
                <!-- User Area -->
                <div class="relative" x-data="{ dropdownOpen: false }" @click.outside="dropdownOpen = false">
                    <a class="flex items-center gap-4" href="#" @click.prevent="dropdownOpen = ! dropdownOpen">
                        <span class="hidden text-right lg:block">
                            <span class="block text-sm font-medium text-white dark:text-white">
                                {{ Auth::user() ? Auth::user()->name : 'Guest User' }}
                            </span>
                            <span class="block text-white text-xs font-medium">{{ Auth::user() ? Auth::user()->role : 'Guest' }}</span>
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
                <a class="m-auto" href="/">
                    <img width="180" src="{{ asset('images/logo/terd_logo.png') }}" alt="Logo" />
                </a>
            @endif
        </div>
    </div>
</header>
