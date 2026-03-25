@extends('layouts.app')

@section('title', 'Reservation Details - CLSU RET Cafeteria')

@section('styles')
    .reservation-hero-bg {
        background-image: url('/images/banner1.jpg');
        background-size: cover;
        background-position: top;
    }

    .status-label {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 9999px;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
    }

    .status-approved {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .status-declined {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .status-pending {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
    }

    .status-cancelled {
        background-color: #e5e7eb;
        color: #374151;
        border: 1px solid #d1d5db;
    }

    .modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(17, 24, 39, 0.6);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        padding: 1rem;
    }

    .modal-backdrop.show {
        display: flex;
    }
@endsection

@section('content')
<section class="reservation-hero-bg py-20 lg:py-20 bg-gray-900 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl lg:text-5xl font-extrabold mb-3 tracking-wide">Reservation Details</h1>
        <p class="text-lg lg:text-xl font-poppins opacity-90">Track the status of your catering requests.</p>
    </div>
</section>

<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-2xl overflow-hidden border border-gray-100">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Your Reservations</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reservation</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Menus</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persons</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($reservations as $reservation)
                            @php
                                $badgeClass = match($reservation->status) {
                                    'approved' => 'status-approved',
                                    'declined' => 'status-declined',
                                    'cancelled' => 'status-cancelled',
                                    default => 'status-pending',
                                };

                                $menuNames = $reservation->items
                                    ->map(fn($item) => optional($item->menu)->name)
                                    ->filter()
                                    ->unique()
                                    ->values();

                                $eventTimeLabel = 'N/A';
                                if (!empty($reservation->event_time)) {
                                    try {
                                        $eventTimeLabel = \Carbon\Carbon::parse($reservation->event_time)->format('h:i A');
                                    } catch (\Throwable $e) {
                                        $eventTimeLabel = (string) $reservation->event_time;
                                    }
                                }
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="font-semibold">#{{ $reservation->id }}</div>
                                    <div class="text-gray-500">{{ $reservation->event_name ?? 'Catering Reservation' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="font-medium">
                                        {{ $reservation->event_date ? $reservation->event_date->format('M d, Y') : 'N/A' }}
                                    </div>
                                    <div class="text-gray-500">
                                        {{ $eventTimeLabel }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @if($menuNames->isEmpty())
                                        <span class="text-gray-500">No menu selected</span>
                                    @else
                                        <span>{{ $menuNames->take(2)->join(', ') }}</span>
                                        @if($menuNames->count() > 2)
                                            <span class="text-gray-500"> +{{ $menuNames->count() - 2 }} more</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $reservation->number_of_persons ?? 0 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-label {{ $badgeClass }}">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                    @if($reservation->status === 'declined' && $reservation->decline_reason)
                                        <p class="mt-1 text-xs text-red-600 max-w-xs">{{ $reservation->decline_reason }}</p>
                                    @endif
                                    @if($reservation->payment_uploaded_at)
                                        <p class="mt-1 text-xs text-green-700">
                                            Receipt uploaded {{ $reservation->payment_uploaded_at->format('M d, Y h:i A') }}
                                        </p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex flex-wrap gap-2">
                                        @if(in_array($reservation->status, ['pending', 'approved']))
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-1 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150 text-xs font-semibold"
                                                data-action="{{ route('reservation.payment.upload', $reservation) }}"
                                                data-receipt-url="{{ $reservation->payment_receipt_path ? asset('storage/'.$reservation->payment_receipt_path) : '' }}"
                                                onclick="openPaymentModal(this)"
                                            >
                                                Payment
                                            </button>
                                        @endif

                                        @if($reservation->status === 'pending')
                                            <form method="POST" action="{{ route('reservation.cancel', $reservation) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-150 text-xs font-semibold">
                                                    Cancel
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">
                                    You have no reservations yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<div id="paymentModal" class="modal-backdrop">
    <div class="bg-white rounded-xl shadow-2xl sm:max-w-lg w-full overflow-hidden">
        <div class="px-6 py-4 bg-green-700 text-white">
            <h3 class="text-xl font-bold">Payment Upload</h3>
            <p class="text-sm opacity-90 mt-1">Upload your payment receipt image (optional).</p>
        </div>

        <form id="paymentForm" method="POST" enctype="multipart/form-data" class="px-6 py-5 space-y-4">
            @csrf
            <div>
                <label for="payment_receipt" class="block text-sm font-medium text-gray-700">Receipt Image</label>
                <input
                    id="payment_receipt"
                    name="payment_receipt"
                    type="file"
                    accept="image/png,image/jpeg,image/jpg,image/webp"
                    class="mt-2 block w-full text-sm text-gray-700 border border-gray-300 rounded-lg p-2"
                >
                <p class="mt-2 text-xs text-gray-500">Optional. Accepted: JPG, PNG, WEBP (max 5MB).</p>
            </div>

            <div id="existingReceiptWrap" class="hidden text-sm">
                <a id="existingReceiptLink" href="#" target="_blank" class="text-indigo-600 hover:text-indigo-800 font-medium">
                    View currently uploaded receipt
                </a>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closePaymentModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-150">
                    Close
                </button>
                <button type="submit" class="px-4 py-2 bg-green-700 text-white rounded-lg hover:bg-green-800 transition duration-150">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const paymentModal = document.getElementById('paymentModal');
    const paymentForm = document.getElementById('paymentForm');
    const existingReceiptWrap = document.getElementById('existingReceiptWrap');
    const existingReceiptLink = document.getElementById('existingReceiptLink');
    const paymentInput = document.getElementById('payment_receipt');

    function openPaymentModal(button) {
        const receiptUrl = button.dataset.receiptUrl || '';
        const actionUrl = button.dataset.action;

        paymentForm.action = actionUrl;
        paymentInput.value = '';

        if (receiptUrl) {
            existingReceiptWrap.classList.remove('hidden');
            existingReceiptLink.href = receiptUrl;
        } else {
            existingReceiptWrap.classList.add('hidden');
            existingReceiptLink.href = '#';
        }

        paymentModal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closePaymentModal() {
        paymentModal.classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    paymentModal.addEventListener('click', function (event) {
        if (event.target === paymentModal) {
            closePaymentModal();
        }
    });
</script>
@endsection
