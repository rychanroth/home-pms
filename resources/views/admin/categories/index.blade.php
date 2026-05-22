<x-app-layout>
    <div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Product Types</h2>
            <a href="{{ route('admin.categories.create') }}" class="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700">+ Add New</a>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Parent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($categories as $cat)
                    <tr>
                        <td class="px-6 py-4">
                            @if($cat->image)
                            <img src="{{ Storage::url($cat->image) }}" class="w-10 h-10 rounded object-cover">
                            @else
                            <span class="text-gray-400">No img</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $cat->name }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $cat->parent->name ?? "Root" }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $cat->productType->name ?? "Unknown" }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-evenly items-center space-x-2">
                                <a href="{{ route('admin.categories.edit', $cat) }}"
                                    class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>

                                <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium"
                                        onclick="return confirm('Are you sure you want to delete this? It cannot be undone.')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>