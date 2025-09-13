<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-800">
        <div class="bg-white rounded-lg shadow-lg flex overflow-hidden w-full max-w-4xl">
            
            <!-- Left Logo -->
            <div class="w-1/2 flex items-center justify-center">
                <img src="{{ asset('images/caf-logo.png') }}" alt="RET Cafeteria"
                     class="max-h-64 object-contain">
            </div>

            <!-- Right Form -->
            <div class="w-1/2 p-10 bg-gray-200">
                <h2 class="text-2xl font-bold text-center mb-6">Register</h2>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" name="name" type="text"
                            class="block mt-1 w-full border-gray-400" required autofocus />
                    </div>

                    <!-- Address -->
                    <div class="mt-4">
                        <x-input-label for="address" :value="__('Address')" />
                        <x-text-input id="address" name="address" type="text"
                            class="block mt-1 w-full border-gray-400" />
                    </div>

                    <!-- Email + Contact -->
                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" name="email" type="email"
                                class="block mt-1 w-full border-gray-400" required />
                        </div>
                        <div>
                            <x-input-label for="contact_no" :value="__('Contact No')" />
                            <x-text-input id="contact_no" name="contact_no" type="text"
                                class="block mt-1 w-full border-gray-400" />
                        </div>
                    </div>

                    <!-- Department -->
                    <div class="mt-4">
                        <x-input-label for="department" :value="__('Department/Office')" />
                        <x-text-input id="department" name="department" type="text"
                            class="block mt-1 w-full border-gray-400" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" name="password" type="password"
                            class="block mt-1 w-full border-gray-400" required />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mt-4">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                            class="block mt-1 w-full border-gray-400" required />
                    </div>

                    <!-- Already have account -->
                    <div class="flex justify-between text-sm text-gray-600 mt-4">
                        <a href="{{ route('login') }}" class="hover:underline">
                            {{ __('Have an account already?') }}
                        </a>
                    </div>

                    <!-- Register Button -->
                    <div class="mt-6">
                        <button type="submit"
                            class="w-full bg-gray-800 text-white py-2 rounded hover:bg-gray-900">
                            {{ __('Register') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
