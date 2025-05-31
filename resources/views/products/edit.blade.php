<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-medium text-dark-100 leading-tight">
            Edit Product
        </h2>
    </x-slot>

    <section class="max-w-7xl mx-auto py-8 px-6 bg-light rounded-2xl shadow-lg text-gray-100">
        <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <x-input-label for="name" :value="__('Name')" class="text-white-100" />
                <x-text-input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name', $product->name) }}"
                    class="mt-1 block w-full bg-gray-900 border border-gray-700 text-white-100 rounded focus:border-blue-500"
                    autocomplete="off"
                />
                @error('name') <p class="text-red-500 mt-1 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <x-input-label for="price" :value="__('Price')" class="text-gray-100" />
                <x-text-input
                    id="price"
                    name="price"
                    type="number"
                    step="0.01"
                    value="{{ old('price', $product->price) }}"
                    class="mt-1 block w-full bg-gray-900 border border-gray-700 text-gray-100 rounded focus:border-blue-500"
                />
                @error('price') <p class="text-red-500 mt-1 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <x-input-label for="status" :value="__('Status')" class="text-gray-100" />
                <select
                    id="status"
                    name="status"
                    class="mt-1 block w-full p-2 rounded bg-gray-900 text-gray-100 border border-gray-700 focus:border-blue-500"
                >
                    <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="archived" {{ old('status', $product->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
                @error('status') <p class="text-red-500 mt-1 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <x-input-label for="launch_date" :value="__('Launch Date')" class="text-gray-100" />
                <x-text-input
                    id="launch_date"
                    name="launch_date"
                    type="date"
                    value="{{ old('launch_date', $product->launch_date) }}"
                    class="mt-1 block w-full bg-gray-900 border border-gray-700 text-gray-100 rounded focus:border-blue-500"
                />
                @error('launch_date') <p class="text-red-500 mt-1 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <x-input-label for="vendor_name" :value="__('Vendor Name')" class="text-gray-100" />
                <x-text-input
                    id="vendor_name"
                    name="vendor_name"
                    type="text"
                    value="{{ old('vendor_name', $product->vendor_name) }}"
                    class="mt-1 block w-full bg-gray-900 border border-gray-700 text-gray-100 rounded focus:border-blue-500"
                />
                @error('vendor_name') <p class="text-red-500 mt-1 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <x-input-label for="vendor_link" :value="__('Vendor Link')" class="text-gray-100" />
                <x-text-input
                    id="vendor_link"
                    name="vendor_link"
                    type="url"
                    value="{{ old('vendor_link', $product->vendor_link) }}"
                    class="mt-1 block w-full bg-gray-900 border border-gray-700 text-gray-100 rounded focus:border-blue-500"
                />
                @error('vendor_link') <p class="text-red-500 mt-1 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <x-input-label for="image" :value="__('Product Image')" class="text-gray-100" />
                @if($product->image)
                    <img src="{{ asset($product->image) }}" alt="Current Image" class="h-12 mb-2 rounded" />
                @endif
                <input
                    id="image"
                    name="image"
                    type="file"
                    accept="image/*"
                    class="mt-1 block w-full text-gray-100 rounded bg-gray-900 border border-gray-700 focus:border-blue-500"
                />
                @error('image') <p class="text-red-500 mt-1 text-sm">{{ $message }}</p> @enderror
            </div>

            <x-primary-button>
                {{ __('Update') }}
            </x-primary-button>
        </form>
    </section>
</x-app-layout>
