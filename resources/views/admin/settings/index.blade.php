<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <h2 class="text-2xl font-bold mb-6">Site Settings</h2>
        <!-- <p class="text-2xl font-bold mb-6">{{ $support_email }}</p> -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-sm space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Pharmacy Name</label>
                <input type="text" name="site_name" value="{{ $site_name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-teal-500 focus:border-teal-500">
            </div>

            <!-- <div>
                <label class="block text-sm font-medium text-gray-700">Support Email</label>
                <input type="email" name="support_email" value="{{ $support_email }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-teal-500 focus:border-teal-500">
            </div> -->

            <div>
                <label class="block text-sm font-medium text-gray-700">Pharmacy Logo</label>
                <input type="file" name="site_logo" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                
                @if($site_logo)
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg inline-block">
                        <p class="text-xs text-gray-500 mb-2">Current Logo:</p>
                        <img src="{{ Storage::url($site_logo) }}" class="h-16 w-auto object-contain">
                    </div>
                @endif
            </div>

            <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded hover:bg-teal-700">Save Settings</button>
        </form>
    </div>
</x-app-layout>