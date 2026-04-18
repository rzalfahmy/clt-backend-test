<x-guest-layout>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Global Error -->
    @if ($errors->any())
        <div class="mb-4 text-sm text-red-500 font-medium">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />

            <x-text-input
                id="email"
                class="block mt-1 w-full !bg-gray-800 !text-white !border-gray-600 placeholder-gray-400 focus:ring-indigo-500 focus:border-indigo-500"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
                placeholder="Enter your email"
            />

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4 relative">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input
                id="password"
                class="block mt-1 w-full pr-10 !bg-gray-800 !text-white !border-gray-600 placeholder-gray-400 focus:ring-indigo-500 focus:border-indigo-500"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Enter your password"
            />

            <!-- Toggle show password -->
            <button
                type="button"
                onclick="togglePassword()"
                class="absolute right-3 top-9 text-gray-400 hover:text-white"
            >
                👁
            </button>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                    @checked(old('remember'))
                    class="rounded border-gray-500 bg-gray-800 text-indigo-500 focus:ring-indigo-500"
                >
                <span class="ms-2 text-sm text-gray-300">
                    Remember me
                </span>
            </label>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between mt-4">

            @if (Route::has('password.request'))
                <a
                    class="text-sm text-gray-400 hover:text-white underline"
                    href="{{ route('password.request') }}"
                >
                    Forgot password?
                </a>
            @endif

            <x-primary-button
                class="ms-3"
                onclick="this.disabled=true; this.form.submit();"
            >
                Log in
            </x-primary-button>
        </div>

    </form>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>

</x-guest-layout>
