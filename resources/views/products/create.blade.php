<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-medium text-dark-100 leading-tight">
            Add New Product
        </h2>
    </x-slot>

    <section class="max-w-7xl mx-auto py-8 px-6 bg-black rounded-2xl shadow-lg text-dark-200">
        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name') }}"
                    class="mt-1 block w-full bg-gray-900 border border-gray-700 text-dark-200 rounded focus:border-blue-500"
                    autocomplete="off"
                />
                @error('name')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <x-input-label for="price" :value="__('Price')" />
                <x-text-input
                    id="price"
                    name="price"
                    type="number"
                    step="0.01"
                    value="{{ old('price') }}"
                    class="mt-1 block w-full bg-gray-900 border border-gray-700 text-dark-200 rounded focus:border-blue-500"
                />
                @error('price')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <x-input-label for="status" :value="__('Status')" />
                <select
                    id="status"
                    name="status"
                    class="mt-1 block w-full p-2 rounded bg-gray-900 text-dark-200 border border-gray-700 focus:border-blue-500"
                >
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
                @error('status')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <x-input-label for="launch_date" :value="__('Launch Date')" />
                <x-text-input
                    id="launch_date"
                    name="launch_date"
                    type="date"
                    value="{{ old('launch_date') }}"
                    class="mt-1 block w-full bg-gray-900 border border-gray-700 text-dark-200 rounded focus:border-blue-500"
                />
                @error('launch_date')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <x-input-label for="vendor_name" :value="__('Vendor Name')" />
                <x-text-input
                    id="vendor_name"
                    name="vendor_name"
                    type="text"
                    value="{{ old('vendor_name') }}"
                    class="mt-1 block w-full bg-gray-900 border border-gray-700 text-dark-200 rounded focus:border-blue-500"
                />
                @error('vendor_name')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <x-input-label for="vendor_link" :value="__('Vendor Link')" />
                <x-text-input
                    id="vendor_link"
                    name="vendor_link"
                    type="url"
                    value="{{ old('vendor_link') }}"
                    class="mt-1 block w-full bg-gray-900 border border-gray-700 text-dark-200 rounded focus:border-blue-500"
                />
                @error('vendor_link')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- New Image Upload Field -->
            <div class="mb-4">
                <x-input-label for="image" :value="__('Product Image')" />
                <input
                    id="image"
                    name="image"
                    type="file"
                    accept="image/*"
                    class="mt-1 block w-full text-dark-200 rounded bg-gray-900 border border-gray-700 focus:border-blue-500"
                />
                @error('image')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <x-primary-button>
                {{ __('Save') }}
            </x-primary-button>
        </form>
    </section>
</x-app-layout>
