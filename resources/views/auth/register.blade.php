<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-slate-100">
        <div class="bg-white rounded-xl shadow-xl flex overflow-hidden w-full max-w-5xl">
            <!-- Left Logo -->
            <div class="hidden md:flex w-1/2 items-center justify-center bg-gradient-to-br from-blue-100 to-slate-200 p-8">
                <img src="{{ asset('images/caf-logo.png') }}" alt="RET Cafeteria"
                     class="max-h-64 object-contain">
            </div>

            <!-- Right Form -->
            <div class="w-full md:w-1/2 p-8 md:p-12">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Create Account</h2>
                    <p class="text-gray-600">Join our cafeteria community</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div class="mb-6">
                        <x-input-label for="name" :value="__('Name')" class="text-gray-700 font-medium" />
                        <div class="relative">
                            <x-text-input id="name" name="name" type="text"
                                class="block mt-1 w-full pl-10 h-12" required autofocus />
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="mb-6">
                        <x-input-label for="address" :value="__('Address')" class="text-gray-700 font-medium" />
                        <div class="relative">
                            <x-text-input id="address" name="address" type="text"
                                class="block mt-1 w-full pl-10 h-12" />
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Email + Contact -->
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium" />
                            <div class="relative">
                                <x-text-input id="email" name="email" type="email"
                                    class="block mt-1 w-full pl-10 h-12" required />
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <x-input-label for="contact_no" :value="__('Contact No')" class="text-gray-700 font-medium" />
                            <div class="relative">
                                <x-text-input id="contact_no" name="contact_no" type="text"
                                    class="block mt-1 w-full pl-10 h-12" />
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Department -->
                    <div class="mb-6">
                        <x-input-label for="department" :value="__('Department/Office')" class="text-gray-700 font-medium" />
                        <div class="relative">
                            <x-text-input id="department" name="department" type="text"
                                class="block mt-1 w-full pl-10 h-12" />
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium" />
                        <div class="relative">
                            <x-text-input id="password" name="password" type="password"
                                class="block mt-1 w-full pl-10 pr-10 h-12" required />
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <button type="button" id="togglePassword1" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg id="eyeIcon1" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-gray-700 font-medium" />
                        <div class="relative">
                            <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                                class="block mt-1 w-full pl-10 pr-10 h-12" required />
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <button type="button" id="togglePassword2" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg id="eyeIcon2" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Already have account -->
                    <div class="flex justify-center text-sm mb-6">
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 hover:underline transition duration-200">
                            {{ __('Have an account already?') }}
                        </a>
                    </div>

                    <!-- Register Button -->
                    <div>
                        <x-primary-button class="w-full justify-center">
                            {{ __('Register') }}
                        </x-primary-button>
                    </div>
                </form>

                <script>
                    // Toggle for password
                    document.getElementById('togglePassword1').addEventListener('click', function () {
                        const passwordInput = document.getElementById('password');
                        const eyeIcon = document.getElementById('eyeIcon1');
                        if (passwordInput.type === 'password') {
                            passwordInput.type = 'text';
                            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>';
                        } else {
                            passwordInput.type = 'password';
                            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
                        }
                    });

                    // Toggle for confirm password
                    document.getElementById('togglePassword2').addEventListener('click', function () {
                        const confirmPasswordInput = document.getElementById('password_confirmation');
                        const eyeIcon = document.getElementById('eyeIcon2');
                        if (confirmPasswordInput.type === 'password') {
                            confirmPasswordInput.type = 'text';
                            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>';
                        } else {
                            confirmPasswordInput.type = 'password';
                            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
                        }
                    });
                </script>
            </div>
        </div>
    </div>
</x-guest-layout>
