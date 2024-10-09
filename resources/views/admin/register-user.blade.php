<x-guest-layout>
    <form id="registerForm">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password and Auto-generate Password Checkbox -->
        <div class="mt-4" x-data="{ autoGenerate: false }">
            <div class="flex items-center my-4">
                <x-input-label for="password" :value="__('Auto Generate Password')" />
                <input id="auto-generate" type="checkbox" x-model="autoGenerate" class="ml-2">
                <label x-show="autoGenerate" for="auto-generate" class="ml-2 text-sm text-green-600 dark:text-green-400">{{ __('Password will be auto-generated') }}</label>
            </div>

            <div x-show="!autoGenerate">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full"
                              type="password"
                              name="password"
                              required
                              autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div x-show="!autoGenerate" class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full"
                              type="password"
                              name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <!-- Auto-generated password will be hidden -->
        <input type="hidden" name="auto_generated_password" x-bind:value="autoGenerate ? '{{ Str::random(12) }}' : ''">

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-4" id="submitButton">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            fetch("{{ route('admin.user.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = "{{ route('admin.users') }}";
                } else {
                    console.log(data.errors);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>
</x-guest-layout>
