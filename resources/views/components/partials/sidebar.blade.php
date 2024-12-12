@auth
    <aside :class="sidebarToggle ? 'translate-x-0' : '-translate-x-full'"
        class="absolute left-0 top-0 z-9999 flex h-screen w-72.5 flex-col overflow-y-hidden bg-black duration-300 ease-linear dark:bg-boxdark lg:static lg:translate-x-0"
        @click.outside="sidebarToggle = false">
        <!-- SIDEBAR HEADER -->
        <!-- <div class="flex items-center justify-between gap-2 px-6 py-5.5 lg:py-6.5">
            <button class="block lg:hidden" @click.stop="sidebarToggle = !sidebarToggle">
                <i class="fa-solid fa-arrow-left"></i>
            </button>
        </div> -->
        <!-- SIDEBAR HEADER -->

        <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear">
            <a class="m-auto" href="/">
                <img class="mt-4" width="250" src="{{ asset('images/logo/terd_logo.png') }}" alt="Logo" />
            </a>
            <!-- Sidebar Menu -->
            <nav class="mt-5 px-4 py-4 lg:mt-9 lg:px-6" x-data="{ selected: '{{ $pageTitle ?? '' }}' }">
                <!-- Customer Menu Group -->
                <div>
                    <!-- <h3 class="mb-4 ml-4 text-sm font-medium text-bodydark2">Customer Menu</h3> -->

                    <ul class="mb-6 flex flex-col gap-1.5">
                        @if(Auth()->user())
                            <!-- Menu Item Deposits -->
                            @if(Auth()->user()->role == 'admin')
                            <!-- Menu Item Dashboard -->
                            <li>
                                <a class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
                                    href="/dashboard"
                                    @click="selected = 'dashboard'"
                                    :class="{ 'bg-graydark dark:bg-meta-4': selected === 'dashboard' || '{{ Request::is('dashboard*') }}' }">
                                    <i class="fa fa-dashboard me-1 text-blue f-10"></i>
                                    Dashboard
                                </a>
                            </li>
                            <!-- Menu Item Dashboard -->

                            <!-- Menu Item Orders -->
                            <!-- <li>
                                <a class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
                                    href="/orders"
                                    @click="selected = 'orders'"
                                    :class="{ 'bg-graydark dark:bg-meta-4': selected === 'view-orders' || '{{ Request::is('orders*') }}' }">
                                    <i class="fa fa-brands fa-shopify"></i>
                                    Orders
                                </a>
                            </li> -->
                            <!-- Menu Item Orders -->
                            <!-- Menu Item Bank -->
                            <li>
                                <a class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
                                    href="/bank"
                                    @click="selected = 'bank'"
                                    :class="{ 'bg-graydark dark:bg-meta-4': selected === 'view-bank' || '{{ Request::is('bank*') }}' }">
                                    <i class="fa-solid fa-building-columns"></i>
                                    Bank
                                </a>
                            </li>
                            <!-- Menu Item Bank -->
                            <!-- Menu Item Transaction -->
                            <li>
                                <a class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
                                    href="/transaction"
                                    @click="selected = 'transaction'"
                                    :class="{ 'bg-graydark dark:bg-meta-4': selected === 'view-transaction' || '{{ Request::is('transaction*') }}' }">
                                    <i class="fa-solid fa-money-bill-transfer"></i>
                                    Transaction
                                </a>
                            </li>
                            <!-- Menu Item Transaction -->
                            <!-- Menu Users Settings -->
                            <li>
                                <a class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
                                    href="/users"
                                    @click="selected = 'users'"
                                    :class="{ 'bg-graydark dark:bg-meta-4': selected === 'view-users' || '{{ Request::is('users*') }}' }">
                                    <i class="fa-solid fa-users"></i>
                                    Users
                                </a>
                            </li>
                            <!-- Menu Users Settings -->
                            <!-- Menu Webforms -->
                            <!-- <li>
                                <a class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
                                    href="/webforms"
                                    @click="selected = 'webforms'"
                                    :class="{ 'bg-graydark dark:bg-meta-4': selected === 'view-webforms' || '{{ Request::is('webforms*') }}' }">
                                    <i class="fa-solid fa-table-list"></i>
                                    Webforms
                                </a>
                            </li> -->
                            <!-- Menu Webforms -->
                            <!-- Menu Item -->
                            <li>
                                <a class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
                                    href="/settings"
                                    @click="selected = 'settings'"
                                    :class="{ 'bg-graydark dark:bg-meta-4': selected === 'view-settings' || '{{ Request::is('settings*') }}' }">
                                    <i class="fa-solid fa-gear"></i>
                                    Settings
                                </a>
                            </li>
                            <!-- Menu Item -->
                            @endif
                        @endif

                        <!-- Menu Item Deposits -->
                        <!-- <li>
                            <a class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
                                href="/deposits"
                                @click="selected = 'deposits'"
                                :class="{ 'bg-graydark dark:bg-meta-4': selected === 'view-deposits' || '{{ Request::is('deposits*') }}' }">
                                <i class="fa-solid fa-hand-holding-dollar"></i>
                                Deposits
                            </a>
                        </li> -->

                        <!-- Menu Item payments -->
                        <!-- <li>
                            <a class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
                                href="{{ route('payments.index') }}"
                                @click="selected = 'payments'"
                                :class="{ 'bg-graydark dark:bg-meta-4': selected === 'view-payments' || '{{ Request::is('payments*') }}' }">
                                <i class="fa-solid fa-money-bill-wave"></i>
                                Payments
                            </a>
                        </li> -->
                        <!-- Menu Item payemnts -->
                    </ul>
                </div>
            </nav>
            <!-- Sidebar Menu -->
        </div>
    </aside>
@else
@endauth
