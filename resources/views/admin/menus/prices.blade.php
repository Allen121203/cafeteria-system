@extends('layouts.sidebar')
@section('page-title','Manage Menu Prices')

@section('content')
<div class="container mx-auto bg-white rounded-xl shadow-lg border border-gray-200 p-6">
  {{-- Header --}}
  <div class="flex items-center mb-6">
    <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
    </svg>
    <h1 class="text-2xl font-bold text-gray-900">Manage Menu Prices</h1>
  </div>

  {{-- Price Form --}}
  <form method="POST" action="{{ route('admin.menus.prices.update') }}" class="space-y-6">
      @csrf

      <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meal Time</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Standard Price</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Special Price</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach($meals as $mealKey => $mealLabel)
              <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $mealLabel }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">₱</span>
                    <input type="number"
                           name="prices[standard][{{ $mealKey }}]"
                           value="{{ $priceMap['standard'][$mealKey] ?? 0 }}"
                           step="0.01"
                           min="0"
                           class="pl-8 w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                           required>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">₱</span>
                    <input type="number"
                           name="prices[special][{{ $mealKey }}]"
                           value="{{ $priceMap['special'][$mealKey] ?? 0 }}"
                           step="0.01"
                           min="0"
                           class="pl-8 w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                           required>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="flex justify-end gap-3 pt-4">
        <a href="{{ route('admin.menus.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-200 font-medium">Cancel</a>
        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium shadow-lg flex items-center">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
          Update Prices
        </button>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectedType = '{{ $selectedType }}';
    const selectedMeal = '{{ $selectedMeal }}';

    if (selectedType && selectedMeal) {
        // Find the row for the selected meal
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const mealCell = row.querySelector('td:first-child');
            if (mealCell && mealCell.textContent.trim().toLowerCase() === selectedMeal.replace('_', ' ')) {
                // Scroll to the row
                row.scrollIntoView({ behavior: 'smooth', block: 'center' });

                // Highlight the row temporarily
                row.style.backgroundColor = '#fef3c7'; // light yellow
                row.style.transition = 'background-color 0.5s ease';

                // Remove highlight after 3 seconds
                setTimeout(() => {
                    row.style.backgroundColor = '';
                }, 3000);

                // Find and focus the specific input for the selected type
                const inputName = `prices[${selectedType}][${selectedMeal}]`;
                const targetInput = document.querySelector(`input[name="${inputName}"]`);

                if (targetInput) {
                    // Focus on the specific input
                    setTimeout(() => {
                        targetInput.focus();
                        targetInput.select();
                    }, 500); // Wait for scroll to complete

                    // Highlight the specific input
                    targetInput.style.borderColor = '#f59e0b'; // amber color
                    targetInput.style.boxShadow = '0 0 0 3px rgba(245, 158, 11, 0.3)';
                    setTimeout(() => {
                        targetInput.style.borderColor = '';
                        targetInput.style.boxShadow = '';
                    }, 3000);
                }
            }
        });
    }
});
</script>
@endsection
