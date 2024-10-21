<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            <span class="mx-4 float-right">
                {{ __('Webforms') }}
            </span>
        </h2>
    </x-slot>

    <x-slot name="slot">
        <div class="flex lg:col-start-2 text-slate-800 dark:text-slate-200 m-4 p-4">
            Webforms
        </div>
        <hr>

        <div class="-mx-3 flex flex-1 m-3 p-3">
            <nav class="-mx-3 flex flex-1 justify-start m-3">
                // links
            </nav>
        </div>
        <hr>

        @if(!$webforms || !isset($webforms->items))
            <div class="flex flex-1 justify-center items-center h-96">
                <div class="text-center">
                    <h2 class="text-2xl font-semibold text-slate-800 dark:text-slate-200">No Webforms Found or Failed to fetch</h2>
                    <p class="text-lg text-slate-600 dark:text-slate-400">Please contact system admin.</p>
                </div>
            </div>
        @endif

        <!-- Table with Filter -->
        <div class="my-4 w-full text-nowrap"  x-data="webformData()">
            <!-- Filters Form -->
            <div class="flex flex-col lg:flex-row gap-4">
                <div class="flex flex-1 mx-2 items-center" x-data="{options: [{value:'createdAt,desc', name:'Dec'}, {value:'createdAt,Acs', name:'Acs'}]}">
                    <label for="orderBy" class="text-sm">Account IDs</label>
                    <select x-model="orderBy" class="mx-4 px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-300 dark:bg-slate-800 dark:text-slate-200 dark:border-slate-600">
                        <option value="">Select Order</option>
                        <template x-for="option in options" :key="option.name">
                            <option x-bind:value="option.value" x-text="option.name"></option>
                        </template>
                    </select>
                </div>
                <button
                    @click="fetchPage(1)"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none"
                >
                    Apply Filters
                </button>
            </div>

            <div class="w-full">
                <div class="w-full -mx-3 m-3 p-3">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-slate-900 border border-slate-800 dark:border-slate-100">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">ID</th>
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">URL</th>
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">Created At</th>
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">Expires At</th>
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">Type</th>
                                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-slate-500 dark:text-slate-100 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="webform in webforms" :key="webform.id">
                                    <tr class="bg-slate-50 dark:bg-slate-800 border-b  border-slate-900 dark:border-slate-50 text-nowrap">
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100" x-text="webform.id"></td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100">
                                            <a :href="webform.url" target="_blank" class="text-blue-500 hover:underline">Open</a>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100" x-text="formatDate(webform.createdAt)"></td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100" x-text="formatDate(webform.expiresAt)"></td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100" x-text="webform.type"></td>
                                        <td x-bind:class="webform.status =='EXPIRED' ? 'bg-red-500' : (webform.status =='COMPLETED'? 'bg-green-500' : 'bg-yellow-500')"
                                            class="px-6 py-4 text-sm text-slate-900 dark:text-slate-50" x-text="webform.status"></td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-100 text-nowrap">
                                            <button
                                                @click="viewWebform(webform.id)"
                                                class="ml-2 text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded"
                                            >
                                                View
                                            </button>
                                        </td>

                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-sm text-slate-500">
                        Showing <span x-text="page"></span> of <span x-text="pageCount"></span> pages
                        <select
                            x-model="perPage"
                            class="w-full lg:w-1/4 ml-2 px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-300 dark:bg-slate-800 dark:text-slate-200 dark:border-slate-600"
                            x-on:change="fetchPage(1)"
                        >
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        webforms per page
                    </div>

                    <div class="space-x-2">
                        <button
                            class="px-4 py-2 bg-slate-300 text-slate-700 rounded"
                            :disabled="page <= 1"
                            @click="previousPage()">
                            Previous
                        </button>
                        <button
                            class="px-4 py-2 bg-slate-300 text-slate-700 rounded"
                            :disabled="page >= pageCount"
                            @click="nextPage()">
                            Next
                        </button>
                    </div>
                </div>
            </div>


            <!-- Modal (hidden by default) -->
            <div
                x-show="showModal"
                style="display: none;"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                @keydown.escape.window="showModal = false"
            >
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-xl max-w-3xl w-full p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Webform Details</h2>
                        <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                            &times;
                        </button>
                    </div>
                    <hr class="my-2">

                    <!-- Modal content (webform details) -->
                    <div>
                        <p><strong>ID:</strong> <span x-text="selectedWebform.id"></span></p>
                        <p><strong>URL:</strong> <a :href="selectedWebform.url" target="_blank" class="text-blue-500 hover:underline" x-text="selectedWebform.url"></a></p>
                        <p><strong>Created At:</strong> <span x-text="selectedWebform.createdAt"></span></p>
                        <p><strong>Expires At:</strong> <span x-text="selectedWebform.expiresAt"></span></p>
                        <p><strong>Type:</strong> <span x-text="selectedWebform.type"></span></p>
                        <p><strong>Status:</strong> <span x-text="selectedWebform.status"></span></p>
                        <p><strong>Payload:</strong> <span x-text="JSON.stringify(selectedWebform.payload) || 'N/A'"></span></p>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button @click="showModal = false" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
          function webformData() {
              return {
                  webforms: @json($webforms->items),
                  page: {{ $webforms->paging->page }},
                  perPage: {{ $webforms->paging->perPage }},
                  pageCount: {{ $webforms->paging->pageCount }},
                  totalCount: {{ $webforms->paging->totalCount }},
                  orderBy : '',
                  showModal: false,
                  selectedWebform: {},

                  fetchPage(page) {
                      showLoading();
                      axios.get("{{ route('admin.webform.get-webforms') }}", {
                          params: { page: page, perPage: this.perPage, order: this.orderBy }
                      })
                      .then(response => {
                          this.webforms = response.data.items;
                          this.page = response.data.paging.page;
                          this.pageCount = response.data.paging.pageCount;
                          this.totalCount = response.data.paging.totalCount;
                          hideLoading();
                      })
                      .catch(error => {
                        console.error('Error fetching webforms:', error);
                        hideLoading();
                      });
                  },

                  previousPage() {
                      if (this.page > 1) {
                          this.page--;
                          this.fetchPage(this.page);
                      }
                  },

                  nextPage() {
                      if (this.page < this.pageCount) {
                          this.page++;
                          this.fetchPage(this.page);
                      }
                  },

                  numberFormat(amount) {
                      return new Intl.NumberFormat().format(amount);
                  },

                  formatDate(dateString) {
                      return new Date(dateString).toISOString().split('T')[0];
                  },

                  viewWebform(webformId) {
                      showLoading();
                      let token = document.head.querySelector('meta[name="csrf-token"]').content;

                      // Fetch webform data from the server
                      fetch(`/webforms/${webformId}`, {
                          method: 'GET',
                          headers: {
                              'Content-Type': 'application/json',
                              'X-CSRF-TOKEN': token
                          },
                      })
                      .then(response => response.json())
                      .then(data => {
                          if (data) {
                              this.selectedWebform = data;
                              this.showModal = true;
                          } else {
                              alert('Failed to fetch webform data');
                          }
                          hideLoading();
                      })
                      .catch(error => {
                          console.error('Error fetching webform data:', error);
                          alert('An error occurred while fetching webform data');
                          hideLoading();
                      });
                  }
              }
          }
        </script>
    </x-slot>
</x-app-layout>
