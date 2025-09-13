<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-800">
        <div class="bg-white rounded-lg shadow-lg flex overflow-hidden w-full max-w-4xl">
            
            <!-- Left side (Logo) -->
            <div class="w-1/2 flex items-center justify-center">
                <img src="{{ asset('images/caf-logo.png') }}" alt="RET Cafeteria"
                     class="max-h-64 object-contain">
            </div>

            <!-- Right side (Login Form) -->
            <div class="w-1/2 p-10 bg-gray-200">
                <h2 class="text-2xl font-bold text-center mb-6">Login</h2>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <!-- Email -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" type="email" name="email"
                            class="block mt-1 w-full border-gray-400"
                            :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" type="password" name="password"
                            class="block mt-1 w-full border-gray-400"
                            required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Forgot + Register -->
                    <div class="flex justify-between text-sm text-gray-600 mt-4">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="hover:underline">
                                {{ __('Forgot Password?') }}
                            </a>
                        @endif
                        <a href="{{ route('register') }}" class="hover:underline">
                            {{ __("Don't have an Account?") }}
                        </a>
                    </div>

                    <!-- Login Button -->
                    <div class="mt-6">
                        <button type="submit"
                            class="w-full bg-gray-800 text-white py-2 rounded hover:bg-gray-900">
                            {{ __('Login') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
