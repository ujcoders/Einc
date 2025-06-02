<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-dark-100 leading-tight">
            Product Dashboard
        </h2>
    </x-slot>

    <!-- Product Table Section -->
    <section class="max-w-7xl mx-auto my-6 py-8 px-6 bg-black rounded-2xl shadow-lg text-gray-100">

        <div class="w-full overflow-x-auto">
            <table class="w-full divide-y divide-gray-700">
                <thead class="bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Vendor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Launch Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Emails Sent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Leads</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-900 divide-y divide-gray-700">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${{ number_format($product->price, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap
                            @if($product->status == 'active') text-green-400
                            @elseif($product->status == 'draft') text-yellow-400
                            @else text-red-400 @endif">
                            {{ ucfirst($product->status) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->vendor_name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->launch_date ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->total_emails_sent ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->total_leads ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" alt="Product Image" class="rounded object-cover" style="width: 300px; height: 100px;" />
                            @else
                                <span class="text-gray-500">No Image</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap flex gap-4">
                            <a href="{{ route('products.edit', $product->id) }}"
                               class="px-3 py-1 rounded bg-blue-700 hover:bg-blue-800 text-white transition duration-200">
                                Edit
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-3 py-1 rounded bg-red-700 hover:bg-red-800 text-white transition duration-200">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </section>

    <!-- Email Form Section -->
   <section class="max-w-7xl mx-auto mt-16 py-8 px-6 bg-black rounded-2xl shadow-lg text-gray-100">


        @if (session('success'))
            <div class="bg-green-600 text-white p-4 rounded shadow mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-600 text-white p-4 rounded shadow mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-yellow-600 text-white p-4 rounded shadow mb-6">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h3 class="text-xl font-semibold mb-6">Send Email to Clients</h3>

        <form action="{{ route('products.sendEmails', $product->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5 max-w-lg">
            @csrf
            <label class="block text-gray-300">Upload CSV/Excel file with emails</label>
            <input type="file" name="email_file" accept=".csv,.xlsx,.xls" required class="text-gray-900 rounded px-3 py-2" />

            <label class="block text-gray-300">Email Subject</label>
            <input type="text" name="subject" required class="px-3 py-2 rounded text-gray-900" />

            <label class="block text-gray-300">Email Body</label>
            <textarea name="body" rows="5" required class="px-3 py-2 rounded text-gray-900"></textarea>

            <button type="submit" class="w-max bg-blue-600 hover:bg-blue-800 text-white px-6 py-2 rounded transition duration-200">
                Send Emails
            </button>
        </form>

    </section>
   <section class="max-w-7xl mx-auto mt-16 py-8 px-6 bg-black rounded-2xl shadow-lg text-gray-100 text-center">

  <a href="{{ route('products.campaign_report', ['id' => $product->id]) }}"
     class="inline-block px-6 py-3 bg-red-600 hover:bg-red-700 rounded-lg text-white font-semibold transition duration-300">
    Click here for reports
  </a>

</section>

</x-app-layout>
