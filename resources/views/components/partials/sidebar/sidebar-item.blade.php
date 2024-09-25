<!-- Menu Item Dashboard -->
<li>
    <a
        class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
        href="#"
        @click.prevent="selected = (selected === 'Dashboard' ? '':'Dashboard')"
        :class="{ 'bg-graydark dark:bg-meta-4': (selected === 'Dashboard') || (page === 'ecommerce' || page === 'analytics' || page === 'stocks') }"
    >
        <i class="fa fa-dashboard me-1 text-blue f-10"></i>
        Dashboard
        <i class="fa fa-chevron-up absolute right-4 top-1/2 -translate-y-1/2 fill-current me-1 text-blue f-10"
        :class="{ 'rotate-180': (selected === 'Dashboard') }"
        width="20"
        height="20"></i>
    </a>

    <!-- Dropdown Menu Start -->
    <div
        class="translate transform overflow-hidden"
        :class="(selected === 'Dashboard') ? 'block' :'hidden'"
    >
        <ul class="mb-5.5 mt-4 flex flex-col gap-2.5 pl-6">
        <li>
            <a
            class="group relative flex items-center gap-2.5 rounded-md px-4 font-medium text-bodydark2 duration-300 ease-in-out hover:text-white"
            href="#"
            :class="page === 'ecommerce' && '!text-white'"
            >Orders
            </a>
        </li>
        <li>
            <a
            class="group relative flex items-center gap-2.5 rounded-md px-4 font-medium text-bodydark2 duration-300 ease-in-out hover:text-white"
            href="#"
            :class="page === 'ecommerce' && '!text-white'"
            :class="page === 'ecommerce' && '!text-white'"
            >Payments
            </a>
        </li>
        </ul>
    </div>
    <!-- Dropdown Menu End -->
</li>
<!-- Menu Item Dashboard -->