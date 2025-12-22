<div id="deleteModal" 
     class="hidden fixed backdrop-blur-sm inset-0 flex items-center justify-center z-50">

    <div class="bg-white p-8 rounded-2xl shadow-xl w-96 text-center text-xl">

        <h2 class="text-2xl font-bold text-gray-800">Are you sure?</h2>
        <p class="text-gray-600 mt-2 text-xl">
            Do you really want to delete this space? This action cannot be undone.
        </p>

        <div class="mt-6 flex justify-center gap-4">
            <!-- Cancel -->
            <button onclick="closeDeleteModal()"
                class="px-6 py-3 bg-emerald-900 text-lg text-white rounded-lg hover:bg-emerald-200 hover:text-black transition shadow text-center font-medium ">
                No
            </button>

            <!-- Confirm -->
                <form action="{{ route('spaces.destroy', $space->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-6 py-3 text-lg bg-red-500 text-white rounded-lg hover:bg-red-300 hover:text-black transition shadow text-center font-medium">
                        Yes, Delete
                    </button>
                </form>
        </div>

    </div>
</div>

<script>
    function openDeleteModal() {
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
