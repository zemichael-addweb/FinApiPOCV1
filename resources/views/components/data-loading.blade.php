<div x-show="Alpine.store('loading').loadingData" class="fixed inset-0 flex items-center justify-center bg-white bg-opacity-75 z-50 cursor-wait">
    <div class="flex flex-col items-center">
        <svg class="animate-spin h-10 w-10 text-slate-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0114.89-2.44l-1.45-1.45A6 6 0 106 12h-2z"></path>
        </svg>
        <p class="mt-2 text-slate-600">Loading...</p>
    </div>
</div>

<script>
    function showLoading() {
        Alpine.store('loading').loadingData = true;
        console.log('loading : ', Alpine.store('loading').loadingData )
    }

    function hideLoading() {
        Alpine.store('loading').loadingData = false;
        console.log('Done Loading : ', Alpine.store('loading').loadingData )
    }
</script>
