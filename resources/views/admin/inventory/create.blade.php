<!-- Modal Background -->
<div x-show="showModal" @click="showModal = false" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak style="display: none;">
    <!-- Modal Box -->
    <div @click.stop class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
        <!-- Close Button -->
        <button @click="showModal = false"
                class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 text-xl">
            &times;
        </button>

        <h2 class="text-xl font-bold mb-4">Add Inventory Item</h2>

        <form action="{{ route('admin.inventory.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Item Name -->
            <div>
                <label for="name" class="block text-sm font-medium">Item Name</label>
                <input type="text" name="name" id="name" required
                    class="w-full border rounded p-2 focus:ring focus:ring-blue-300">
            </div>

            <!-- Category -->
            <div>
                <label for="category" class="block text-sm font-medium">Category</label>
                <select name="category" id="category" required
                    class="w-full border rounded p-2 focus:ring focus:ring-blue-300">
                    <option value="">-- Select Category --</option>
                    <option value="Perishable">Perishable</option>
                    <option value="Condiments">Condiments</option>
                    <option value="Frozen">Frozen</option>
                    <option value="Beverages">Beverages</option>
                    <option value="Others">Others</option>
                </select>
            </div>

            <!-- Quantity -->
            <div>
                <label for="qty" class="block text-sm font-medium">Quantity</label>
                <input type="number" name="qty" id="qty" min="1" required
                    class="w-full border rounded p-2 focus:ring focus:ring-blue-300">
            </div>

            <!-- Unit -->
            <div>
                <label for="unit" class="block text-sm font-medium">Unit</label>
                <select name="unit" id="unit" required
                    class="w-full border rounded p-2 focus:ring focus:ring-blue-300">
                    <option value="">-- Select Unit --</option>
                    <option value="Pieces">Pieces</option>
                    <option value="Kgs">Kgs</option>
                    <option value="Liters">Liters</option>
                    <option value="Packs">Packs</option>
                </select>
            </div>

            <!-- Expiry Date -->
            <div>
                <label for="expiry_date" class="block text-sm font-medium">Expiry Date</label>
                <input type="date" name="expiry_date" id="expiry_date"
                    class="w-full border rounded p-2 focus:ring focus:ring-blue-300">
                <small class="text-gray-500">Leave blank if not applicable.</small>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" 
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Save Item
                </button>
            </div>
        </form>
    </div>
</div>
