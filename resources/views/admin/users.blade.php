<x-app-layout>
    <x-slot name="slot">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-title-md2 font-bold text-black dark:text-white">
                Users
            </h1>
        </div>
        <!-- <div class="-mx-3 flex flex-1 m-3 p-3">
            <nav class="-mx-3 flex flex-1 justify-start m-3">
                <a
                    href="{{ url('/payments/create') }}"
                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    Make Payment
                </a>
                <a
                    href="{{ route('admin.user.register') }}"
                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    Register B2B user
                </a>
            </nav>
        </div>
        <hr> -->

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-white uppercase bg-slate-900 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Email</th>
                        <th scope="col" class="px-6 py-3">Role</th>
                        <!-- <th scope="col" class="px-6 py-3">Actions</th> -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="border dark:border-slate-700">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $user->name }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $user->email }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <span x-data="{ editing: false, role: '{{ $user->role }}' }">
                                    <span x-show="!editing">{{ $user->role }}</span>
                                    <select x-show="editing" x-model="role" class="block w-full text-sm dark:bg-slate-700">
                                        <option value="user">User</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                    <button x-show="editing" @click="editing = false" class="text-green-500 hover:text-green-700">Save</button>
                                    <button @click="editing = true" x-show="!editing" class="text-blue-500 hover:text-blue-700">Change Role</button>
                                </span>
                            </td>
                            <!-- <td class="px-6 py-4 font-medium text-gray-900 flex space-x-2">
                                <button class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-700" @click="viewUser({{ $user->id }})">View</button>
                                <button class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-700" @click="editUser({{ $user->id }})">Edit</button>
                                <button class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-700" @click="deleteUser({{ $user->id }})">Delete</button>
                            </td> -->
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        <script>
            function viewUser(userId) {
                // Handle viewing the user
                window.location.href = `/users/${userId}`;
            }

            function editUser(userId) {
                // Handle editing the user
                window.location.href = `/users/${userId}/edit`;
            }

            function deleteUser(userId) {
                if (confirm('Are you sure you want to delete this user?')) {
                    // Handle user deletion via an API call or form submission
                    // Example: axios.delete(`/users/${userId}`).then(response => console.log(response));
                }
            }
        </script>
    </x-slot>
</x-app-layout>
