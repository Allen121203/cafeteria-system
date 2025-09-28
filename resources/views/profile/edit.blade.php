@extends('layouts.sidebar')

@section('page-title', 'Account Settings')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-xl font-bold mb-6">Account Settings</h2>

    <!-- Password Success Modal -->
    <x-modal name="password-success" :show="session('status') == 'password-updated'">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">Success</h2>
            <p class="mt-4 text-sm text-gray-600">Password successfully changed!</p>
            <div class="mt-6 flex justify-end">
                <button type="button" @click="$dispatch('close-modal', 'password-success')" class="bg-gray-500 text-white px-4 py-2 rounded">Close</button>
            </div>
        </div>
    </x-modal>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Profile Information -->
        <div>
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Profile Information</h3>
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')

                <!-- Full Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                           class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500" required>
                </div>

                <!-- Personal Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Personal Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                           class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500" required>
                </div>

                <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">Update Profile</button>
            </form>
        </div>

        <!-- Reset Password -->
        <div>
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Reset Password</h3>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('put')

                <!-- Current Password -->
                <div class="mb-4">
                    <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                    <input type="password" id="current_password" name="current_password"
                           class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500" required>
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                    <input type="password" id="password" name="password"
                           class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500" required>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500" required>
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">Update Password</button>
            </form>

        </div>
        
    </div>

</div>
@endsection
