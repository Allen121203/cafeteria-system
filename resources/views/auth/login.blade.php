<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-slate-100">
        <div class="bg-white rounded-xl shadow-xl flex overflow-hidden w-full max-w-5xl">
            <!-- Left side (Logo) -->
            <div class="hidden md:flex w-1/2 items-center justify-center bg-gradient-to-br from-blue-100 to-slate-200 p-8">
                <img src="{{ asset('images/caf-logo.png') }}" alt="RET Cafeteria"
                     class="max-h-64 object-contain">
            </div>

            <!-- Right side (Login Form) -->
            <div class="w-full md:w-1/2 p-8 md:p-12">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Welcome Back</h2>
                    <p class="text-gray-600">Sign in to your account</p>
                </div>

                <x-auth-session-status class="mb-6" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <!-- Email -->
                    <div class="mb-6">
                        <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium" />
                        <div class="relative">
                            <x-text-input id="email" type="email" name="email"
                                class="block mt-1 w-full pl-10 h-12"
                                :value="old('email')" required autofocus autocomplete="username" />
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium" />
                        <div class="relative">
                            <x-text-input id="password" type="password" name="password"
                                class="block mt-1 w-full pl-10 pr-10 h-12"
                                required autocomplete="current-password" />
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <button type="button" id="togglePassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg id="eyeIcon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Forgot + Register -->
                    <div class="flex justify-between items-center text-sm mb-6">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-blue-600 hover:text-blue-800 hover:underline transition duration-200">
                                {{ __('Forgot Password?') }}
                            </a>
                        @endif
                        <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 hover:underline transition duration-200">
                            {{ __("Don't have an Account?") }}
                        </a>
                    </div>

                    <!-- Login Button -->
                    <div>
                        <x-primary-button class="w-full justify-center">
                            {{ __('Login') }}
                        </x-primary-button>
                    </div>
                </form>

                <script>
                    document.getElementById('togglePassword').addEventListener('click', function () {
                        const passwordInput = document.getElementById('password');
                        const eyeIcon = document.getElementById('eyeIcon');
                        if (passwordInput.type === 'password') {
                            passwordInput.type = 'text';
                            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>';
                        } else {
                            passwordInput.type = 'password';
                            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
                        }
                    });
                </script>
            </div>
        </div>
    </div>
</x-guest-layout>
