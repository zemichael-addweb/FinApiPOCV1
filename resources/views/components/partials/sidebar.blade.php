@auth
    <aside :class="sidebarToggle ? 'translate-x-0' : '-translate-x-full'"
        class="absolute left-0 top-0 z-9999 flex h-screen w-72.5 flex-col overflow-y-hidden bg-black duration-300 ease-linear dark:bg-boxdark lg:static lg:translate-x-0"
        @click.outside="sidebarToggle = false">
        <!-- SIDEBAR HEADER -->
        <div class="flex items-center justify-between gap-2 px-6 py-5.5 lg:py-6.5">
            <button class="block lg:hidden" @click.stop="sidebarToggle = !sidebarToggle">
                <i class="fa-solid fa-arrow-left"></i>
            </button>
        </div>
        <!-- SIDEBAR HEADER -->

        <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear">
            <a class="m-auto" href="/">
                <img width="176" height="32" src="{{ asset('images/logo/fin-API-Logo_RGB.png') }}" alt="Logo" />
            </a>
            <!-- Sidebar Menu -->
            <nav class="mt-5 px-4 py-4 lg:mt-9 lg:px-6" x-data="{ selected: $persist('Dashboard') }">
                <!-- Customer Menu Group -->
                <div>
                    <h3 class="mb-4 ml-4 text-sm font-medium text-bodydark2">Customer Menu</h3>

                    <ul class="mb-6 flex flex-col gap-1.5">
                        <!-- Menu Item Dashboard -->
                        <li>
                            <a class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
                                href="/dashboard" @click="selected = (selected === 'Dashboard' ? '':'Dashboard')"
                                :class="{ 'bg-graydark dark:bg-meta-4': (selected === 'dashboard') && (
                                    page === 'dashboard') }">
                                <i class="fa fa-dashboard me-1 text-blue f-10"></i>
                                Dashboard
                            </a>
                        </li>
                        <!-- Menu Item Dashboard -->

                        <!-- Menu Item Orders -->
                        <li>
                            <a class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
                                href="/orders" @click="selected = (selected === 'Orders' ? '':'Orders')"
                                :class="{ 'bg-graydark dark:bg-meta-4': (selected === 'Orders') && (page === 'orders') }"
                                :class="page === 'orders' && 'bg-graydark'">
                                <i class="fa fa-brands fa-shopify"></i>
                                Orders
                            </a>
                        </li>
                        <!-- Menu Item Orders -->

                        <!-- Menu Item Payments -->
                        <li>
                            <a class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
                                href="#" @click.prevent="selected = (selected === 'payment' ? '':'payment')"
                                :class="{ 'bg-graydark dark:bg-meta-4': (selected === 'payment') || (
                                        page === 'ecommerce' || page === 'analytics' || page === 'stocks') }">
                                <i class="fa-solid fa-money-bill-wave"></i>
                                payment
                                <i class="fa fa-chevron-up absolute right-4 top-1/2 -translate-y-1/2 fill-current me-1 text-blue f-10"
                                    :class="{ 'rotate-180': (selected === 'payment') }" width="20"
                                    height="20"></i>
                            </a>

                            <!-- Dropdown Menu Start -->
                            <div class="translate transform overflow-hidden"
                                :class="(selected === 'payment') ? 'block' : 'hidden'">
                                <ul class="mb-5.5 mt-4 flex flex-col gap-2.5 pl-6">
                                    <li>
                                        <a class="group relative flex items-center gap-2.5 rounded-md px-4 font-medium text-bodydark2 duration-300 ease-in-out hover:text-white"
                                            href="{{ route('payments.create') }}" :class="page === 'payments' && '!text-white'">Make
                                            Payment
                                        </a>
                                    </li>
                                    <li>
                                        <a class="group relative flex items-center gap-2.5 rounded-md px-4 font-medium text-bodydark2 duration-300 ease-in-out hover:text-white"
                                            href="{{ route('payments.index') }}" :class="page === 'payments' && '!text-white'"
                                            :class="page === 'payments' && '!text-white'">Payments
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- Dropdown Menu End -->
                        </li>
                        <!-- Menu Item Payments -->

                        <!-- Menu Item Deposits -->
                        <li>
                            <a class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
                                href="/deposits" @click="selected = (selected === 'deposits' ? '':'Deposits')"
                                :class="{ 'bg-graydark dark:bg-meta-4': (selected === 'deposits') && (page === 'deposits') }"
                                :class="page === 'deposits' && 'bg-graydark'">
                                <i class="fa-solid fa-hand-holding-dollar"></i>
                                Deposits
                            </a>
                        </li>
                        <!-- Menu Item Deposits -->

                        <!-- Menu Item Profile -->
                        <li>
                            <a class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
                                href="/profile" @click="selected = (selected === 'perofile' ? '':'Profile')"
                                :class="{ 'bg-graydark dark:bg-meta-4': (selected === 'profile') && (
                                    page === 'profile') }"
                                :class="page === 'profile' && 'bg-graydark'">
                                <i class="fa-solid fa-user"></i>
                                Profile
                            </a>
                        </li>
                        <!-- Menu Item Profile -->

                        <!-- Menu Item Settings -->
                        <li>
                            <a class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
                                href="/settings" @click="selected = (selected === 'Settings' ? '':'Settings')"
                                :class="{ 'bg-graydark dark:bg-meta-4': (selected === 'Settings') && (
                                    page === 'settings') }"
                                :class="page === 'settings' && 'bg-graydark'">
                                <i class="fa-solid fa-gear"></i>
                                Settings
                            </a>
                        </li>
                        <!-- Menu Item Settings -->
                    </ul>
                </div>
            </nav>
            <!-- Sidebar Menu -->

            <!-- Promo Box -->
            <div
                class="mx-auto mb-10 w-full max-w-60 rounded-sm border border-strokedark bg-boxdark px-4 py-6 text-center shadow-default">
                <!-- Promo HERE -->
            </div>
            <!-- Promo Box -->
        </div>
    </aside>
@else
@endauth
