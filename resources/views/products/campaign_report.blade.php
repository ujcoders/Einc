<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-100 leading-tight">
            Product Dashboard
        </h2>
    </x-slot>

    <section class="max-w-7xl mx-auto my-12 py-8 px-6 bg-black rounded-2xl shadow-lg text-gray-100">
        <h1 class="text-3xl font-semibold mb-8 border-b border-gray-700 pb-4">
            Campaign Report - {{ $product->name }}
        </h1>

        <div class="grid md:grid-cols-2 gap-10">

            <!-- Emails Sent Table -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Emails Sent</h2>
                <table class="w-full text-left border-collapse border border-gray-700">
                    <thead>
                        <tr class="bg-gray-900">
                            <th class="border border-gray-700 px-4 py-2">Recipient Email</th>
                            <th class="border border-gray-700 px-4 py-2">Subject</th>
                            <th class="border border-gray-700 px-4 py-2">Sent At</th>
                            <th class="border border-gray-700 px-4 py-2">Body Preview</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($emailLogs as $log)
                            <tr class="hover:bg-gray-800">
                                <td class="border border-gray-700 px-4 py-2 break-words">{{ $log->recipient_email }}</td>
                                <td class="border border-gray-700 px-4 py-2">{{ Str::limit($log->subject, 50) }}</td>
                                <td class="border border-gray-700 px-4 py-2">{{ $log->sent_at }}</td>
                                <td class="border border-gray-700 px-4 py-2">{{ Str::limit(strip_tags($log->body), 80) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="border border-gray-700 px-4 py-2 text-center text-gray-500">
                                    No emails sent yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $emailLogs->links() }}</div>
            </div>
        </div>
    </section>

     <section class="max-w-7xl mx-auto mt-16 py-8 px-6 bg-black rounded-2xl shadow-lg text-gray-100">
        <!-- Leads Table -->
        <div>
            <h2 class="text-xl font-semibold mb-4">New Leads</h2>
            <table class="w-full text-left border-collapse border border-gray-700">
                <thead>
                    <tr class="bg-gray-900">
                        <th class="border border-gray-700 px-4 py-2">Name</th>
                        <th class="border border-gray-700 px-4 py-2">Email</th>
                        <th class="border border-gray-700 px-4 py-2">Phone</th>
                        <th class="border border-gray-700 px-4 py-2">Source</th>
                        <th class="border border-gray-700 px-4 py-2">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leads as $lead)
                        <tr class="hover:bg-gray-800">
                            <td class="border border-gray-700 px-4 py-2">{{ $lead->name ?? '-' }}</td>
                            <td class="border border-gray-700 px-4 py-2 break-words">{{ $lead->email }}</td>
                            <td class="border border-gray-700 px-4 py-2">{{ $lead->phone ?? '-' }}</td>
                            <td class="border border-gray-700 px-4 py-2 capitalize">{{ $lead->source }}</td>
                            <td class="border border-gray-700 px-4 py-2">{{ $lead->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="border border-gray-700 px-4 py-2 text-center text-gray-500">
                                No leads yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">{{ $leads->links() }}</div>
        </div>
    </section>
</x-app-layout>
