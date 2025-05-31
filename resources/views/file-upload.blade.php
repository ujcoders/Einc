<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-medium text-gray-900 leading-tight">
            File Upload & Import
        </h2>
    </x-slot>

    <section class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8 bg-white rounded-2xl shadow-lg">

        @if(session('success'))
            <div class="mb-6 text-green-600 font-semibold bg-green-100 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('file.upload.post') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <x-input-label for="upload_file" :value="__('Choose File')" />
                <input
                    type="file"
                    name="upload_file"
                    id="upload_file"
                    class="mt-1 block w-full rounded-md border border-gray-300 bg-gray-50 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
                @error('upload_file')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-input-label for="brk" :value="__('BRK (Optional)')" />
                <x-text-input
                    type="text"
                    name="brk"
                    id="brk"
                    placeholder="Enter BRK value"
                    class="mt-1 block w-full"
                />
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button>
                    {{ __('Upload and Import') }}
                </x-primary-button>
            </div>
        </form>
    </section>
</x-app-layout>
