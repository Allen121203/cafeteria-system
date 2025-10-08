@extends('layouts.sidebar')
@section('page-title','Manage Menu Prices')

@section('content')
<div class="space-y-6">
  {{-- Header --}}
  <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
    <div class="flex items-center gap-4 flex-wrap w-full">
      <a href="{{ route('admin.menus.index') }}" class="flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
      </a>

      <div class="flex items-center">
        <h1 class="text-2xl font-bold text-gray-900">Manage Menu Prices</h1>
      </div>
    </div>

  {{-- Price Form --}}
  <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
    <form method="POST" action="{{ route('admin.menus.prices.update') }}" class="space-y-6">
      @csrf

      <div class="overflow-x-auto">
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
