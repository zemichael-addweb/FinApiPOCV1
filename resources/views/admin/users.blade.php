<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <x-slot name="slot">
        <hr>
        <div class="-mx-3 flex flex-1 m-3 p-3">
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
        <hr>
        <div class="-mx-3 flex flex-1 m-3 p-3">
            <div class="w-full">
                <table class="min-w-full table-auto text-left border-collapse">
                    <thead class="bg-slate-100 dark:bg-slate-800">
                        <tr>
                            <th class="px-4 py-2 border dark:border-slate-600">Name</th>
                            <th class="px-4 py-2 border dark:border-slate-600">Email</th>
                            <th class="px-4 py-2 border dark:border-slate-600">Role</th>
                            <th class="px-4 py-2 border dark:border-slate-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr class="border dark:border-slate-700">
                            <td class="px-4 py-2 border dark:border-slate-600">{{ $user->name }}</td>
                            <td class="px-4 py-2 border dark:border-slate-600">{{ $user->email }}</td>
                            <td class="px-4 py-2 border dark:border-slate-600">
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
                            <td class="px-4 py-2 border dark:border-slate-600 flex space-x-2">
                                <button class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-700" @click="viewUser({{ $user->id }})">View</button>
                                <button class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-700" @click="editUser({{ $user->id }})">Edit</button>
                                <button class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-700" @click="deleteUser({{ $user->id }})">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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
