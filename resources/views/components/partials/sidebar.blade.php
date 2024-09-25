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
          <img width="176" height="32" {{ asset('images/logo/fin-API-Logo_RGB.png') }}" alt="Logo"/>
        </a>    
        <!-- Sidebar Menu -->
        <nav class="mt-5 px-4 py-4 lg:mt-9 lg:px-6" x-data="{ selected: $persist('Dashboard') }">
            @if(Auth::user() && Auth::user()->role=='admin')
            <!-- Admin Menu Group -->
            <div>
                <h3 class="mb-4 ml-4 text-sm font-medium text-bodydark2">Admin Menu</h3>

                <ul class="mb-6 flex flex-col gap-1.5">
                    <!-- Menu Item Dashboard -->
                    <li>
                        <a class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
                            href="#" @click="selected = (selected === 'Dashboard' ? '':'Dashboard')"
                            :class="{ 'bg-graydark dark:bg-meta-4': (selected === 'Dashboard') && (page === 'dashboard') }">
                            <i class="fa fa-dashboard me-1 text-blue f-10"></i>
                            Dashboard
                        </a>
                    </li>
                    <!-- Menu Item Dashboard -->

                    <!-- Menu Item Orders -->
                    <li>
                        <a class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
                            href="/admin/orders" @click="selected = (selected === 'Orders' ? '':'Orders')"
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
                            href="/admin/payments" @click="selected = (selected === 'Payments' ? '':'Payments')"
                            :class="{ 'bg-graydark dark:bg-meta-4': (selected === 'Payments') && (page === 'payments') }"
                            :class="page === 'payments' && 'bg-graydark'">
                            <i class="fa-solid fa-money-bill-wave"></i>
                            Payments
                        </a>
                    </li>
                    <!-- Menu Item Payments -->

                    <!-- Menu Item Deposits -->
                    <li>
                      <a class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
                          href="/admin/deposits" @click="selected = (selected === 'deposits' ? '':'Deposits')"
                          :class="{ 'bg-graydark dark:bg-meta-4': (selected === 'deposits') && (page === 'deposits') }"
                          :class="page === 'deposits' && 'bg-graydark'">
                          <i class="fa-solid fa-hand-holding-dollar"></i>
                          Deposits
                      </a>
                    </li>
                    <!-- Menu Item Deposits -->

                    <!-- Menu Item Users -->
                    <li>
                        <a class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
                            href="/admin/users" @click="selected = (selected === 'Users' ? '':'Users')"
                            :class="{ 'bg-graydark dark:bg-meta-4': (selected === 'Users') && (page === 'users') }"
                            :class="page === 'users' && 'bg-graydark'">
                            <i class="fa-solid fa-users"></i>
                            Users
                        </a>
                    </li>
                    <!-- Menu Item Users -->

                    <!-- Menu Item Settings -->
                    <li>
                        <a class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
                            href="/admin/settings" @click="selected = (selected === 'Settings' ? '':'Settings')"
                            :class="{ 'bg-graydark dark:bg-meta-4': (selected === 'Settings') && (page === 'settings') }"
                            :class="page === 'settings' && 'bg-graydark'">
                            <i class="fa-solid fa-gear"></i>
                            Settings
                        </a>
                    </li>
                    <!-- Menu Item Settings -->
                </ul>
            </div>
            @else
            <!-- Customer Menu Group -->
            <div>
                <h3 class="mb-4 ml-4 text-sm font-medium text-bodydark2">Customer Menu</h3>

                <ul class="mb-6 flex flex-col gap-1.5">
                  <!-- Menu Item Dashboard -->
                  <li>
                      <a class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
                          href="/dashboard" @click="selected = (selected === 'dashboard' ? '':'dashboard')"
                          :class="{ 'bg-graydark dark:bg-meta-4': (selected === 'dashboard') && (page === 'dashboard') }">
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
                          href="/payments" @click="selected = (selected === 'Payments' ? '':'Payments')"
                          :class="{ 'bg-graydark dark:bg-meta-4': (selected === 'Payments') && (page === 'payments') }"
                          :class="page === 'payments' && 'bg-graydark'">
                          <i class="fa-solid fa-money-bill-wave"></i>
                          Payments
                      </a>
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
                          :class="{ 'bg-graydark dark:bg-meta-4': (selected === 'profile') && (page === 'profile') }"
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
                          :class="{ 'bg-graydark dark:bg-meta-4': (selected === 'Settings') && (page === 'settings') }"
                          :class="page === 'settings' && 'bg-graydark'">
                          <i class="fa-solid fa-gear"></i>
                          Settings
                      </a>
                  </li>
                  <!-- Menu Item Settings -->
              </ul>
            </div>
            @endif
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