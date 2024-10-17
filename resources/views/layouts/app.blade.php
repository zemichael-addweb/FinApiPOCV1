<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free-6.5.2-web/css/all.min.css') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body
    x-data="{page: 'ecommerce', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
    x-init="
         darkMode = JSON.parse(localStorage.getItem('darkMode'));
         $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)));"
    :class="{'dark text-bodydark bg-boxdark-2': darkMode === true}"
  >
    <!-- ===== Preloader Start ===== -->
    <x-partials.preloader />
    <!-- ===== Preloader End ===== -->

    <!-- ===== Page Wrapper Start ===== -->
    <div class="flex h-screen overflow-hidden">
      <!-- ===== Sidebar Start ===== -->
      <x-partials.sidebar />
      <!-- ===== Sidebar End ===== -->

      <!-- ===== Content Area Start ===== -->
      <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
        <!-- ===== Header Start ===== -->
        <x-partials.header />
        <!-- ===== Header End ===== -->

        <!-- ===== Main Content Start ===== -->
        <main>
          <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
            @isset($header)
                <div class="font-semibold text-xl leading-tight mx-auto py-6 px-4 sm:px-6 lg:px-8 text-slate-800 dark:text-slate-200 bg-slate-100 dark:bg-slate-800  shadow">
                    {{ $header }}
                </div>
            @endisset
            <x-data-loading />

            {{ $slot }}
          </div>
        </main>
        <!-- ===== Main Content End ===== -->
      </div>
      <!-- ===== Content Area End ===== -->
    </div>
    <!-- ===== Page Wrapper End ===== -->

    <!-- Font Awesome -->
    <script src="{{ asset('vendor/fontawesome-free-6.5.2-web/js/all.min.js') }}"></script>
  </body>
</html>
