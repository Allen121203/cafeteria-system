<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-gray-800 to-gray-900">
        <div class="bg-white rounded-xl shadow-xl flex overflow-hidden w-full max-w-4xl">
            <!-- Left Logo -->
            <div class="hidden md:flex w-1/2 items-center justify-center bg-gradient-to-br from-green-100 to-green-200 p-8">
                <img src="{{ asset('images/caf-logo.png') }}" alt="RET Cafeteria"
                     class="max-h-64 object-contain">
            </div>

            <!-- Right Form -->
            <div class="w-full md:w-1/2 p-8 md:p-12 h-[500px] overflow-y-auto">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Email Verification</h2>
                    <p class="text-gray-600">Please verify your email address</p>
                </div>

                <div class="mb-4 text-sm text-gray-600">
                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email, we will gladly send you another.') }}
                </div>

                @if (session('resent'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ __('A fresh verification link has been sent to your email address.') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.send') }}" class="mb-6">
                    @csrf

                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        {{ __('Resend Verification Email') }}
                    </button>
                </form>

                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-4">Or verify manually if you have the verification code:</p>
                    <a href="{{ route('verification.manual') }}" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline inline-block text-center">
                        {{ __('Verify Manually') }}
                    </a>
                </div>

                <div class="mt-6 text-center">
                    <a href="{{ route('logout') }}" class="text-blue-600 hover:text-blue-800 hover:underline transition duration-200">
                        {{ __('Logout') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Automatically send verification email on page load
        document.addEventListener('DOMContentLoaded', function() {
            fetch('{{ route("verification.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                console.log('Verification email sent automatically');
            })
            .catch(error => {
                console.error('Error sending verification email:', error);
            });
        });
    </script>
</x-guest-layout>
