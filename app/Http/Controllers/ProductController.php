<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductLead;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProductEmailLog;
use Yajra\DataTables\DataTables;
use App\Mail\ProductCampaignEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ProductController extends Controller
{
    public function index()
    {
        $columns = Schema::getColumnListing((new Product)->getTable());
        return view('products.index', compact('columns'));
    }

    public function data(Request $request)
    {
        $columns = Schema::getColumnListing((new Product)->getTable());

        $query = Product::select($columns);

        return DataTables::of($query)
            ->filter(function ($query) use ($request, $columns) {
                foreach ($request->input('columns', []) as $column) {
                    $colName = $column['data'] ?? null;
                    $searchValue = $column['search']['value'] ?? null;

                    if ($colName && in_array($colName, $columns) && !empty($searchValue)) {
                        $query->where($colName, 'LIKE', "%{$searchValue}%");
                    }
                }

                $globalSearch = $request->input('search.value');
                if (!empty($globalSearch)) {
                    $query->where(function ($q) use ($columns, $globalSearch) {
                        foreach ($columns as $col) {
                            $q->orWhere($col, 'LIKE', "%{$globalSearch}%");
                        }
                    });
                }
            })
            ->make(true);
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'price'        => 'required|numeric|min:0',
            'status'       => 'required|in:draft,active,archived',
            'launch_date'  => 'nullable|date',
            'vendor_name'  => 'nullable|string|max:255',
            'vendor_link'  => 'nullable|url',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // max 2MB
        ]);

        if ($request->hasFile('image')) {
            $imageName = Str::uuid() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images/products'), $imageName);
            $validated['image'] = 'images/products/' . $imageName;
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.dashboard', compact('product'));
    }
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'price'        => 'required|numeric|min:0',
            'status'       => 'required|in:draft,active,archived',
            'launch_date'  => 'nullable|date',
            'vendor_name'  => 'nullable|string|max:255',
            'vendor_link'  => 'nullable|url',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = Product::findOrFail($id);

        if ($request->hasFile('image')) {
            $imageName = \Illuminate\Support\Str::uuid() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images/products'), $imageName);
            $validated['image'] = 'images/products/' . $imageName;
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        // Optionally delete image file too here if needed
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
    public function sendEmails(Request $request, $id)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        $product = Product::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'email_file' => 'required|file|mimes:csv,xlsx,xls',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $path = $request->file('email_file')->store('temp');
        $contacts = $this->extractContactsFromFile(storage_path('app/' . $path));

        if (empty($contacts)) {
            return redirect()->back()->with('error', 'No valid emails found in the uploaded file.');
        }

        $sentCount = 0;
        $leadCount = 0;
        $failedEmails = [];

        foreach ($contacts as $contact) {
            $email = $contact['email'];
            $name = $contact['name'] ?? null;
            $phone = $contact['phone'] ?? null;

            try {
                Mail::to($email)->send(new ProductCampaignEmail($request->input('body'), $request->input('subject')));
                $sentCount++;

                $lead = ProductLead::firstOrCreate([
                    'product_id' => $product->id,
                    'email' => $email,
                ], [
                    'name' => $name,
                    'phone' => $phone,
                    'source' => 'email_campaign',
                ]);

                if ($lead->wasRecentlyCreated) {
                    $leadCount++;
                }

                ProductEmailLog::create([
                    'product_id' => $product->id,
                    'recipient_email' => $email,
                    'subject' => $request->input('subject'),
                    'body' => $request->input('body'),
                    'sent_at' => now(),
                ]);
                sleep(3);
            } catch (\Exception $e) {
                $failedEmails[] = [
                    'email' => $email,
                    'error' => $e->getMessage(),
                ];
            }
        }

        $product->increment('total_emails_sent', $sentCount);
        $product->increment('total_leads', $leadCount);
        unlink(storage_path('app/' . $path));

        $message = "Emails sent: $sentCount, New leads added: $leadCount";
        if (!empty($failedEmails)) {
            Log::error('Email send failures:', $failedEmails);
            $message .= ". Some emails failed to send.";
        }

        return redirect()->back()->with('success', $message);
    }
    private function extractContactsFromFile($filePath)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        $contacts = [];
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        if (in_array($extension, ['xlsx', 'xls'])) {
            $rows = Excel::toArray([], $filePath);
            foreach ($rows[0] as $row) {
                $email = $row[0] ?? null;
                $name = $row[1] ?? null;
                $phone = $row[2] ?? null;

                if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $contacts[] = [
                        'email' => trim($email),
                        'name' => trim($name),
                        'phone' => trim($phone),
                    ];
                }
            }
        } elseif ($extension === 'csv') {
            if (($handle = fopen($filePath, 'r')) !== false) {
                while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                    $email = $data[0] ?? null;
                    $name = $data[1] ?? null;
                    $phone = $data[2] ?? null;

                    if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $contacts[] = [
                            'email' => trim($email),
                            'name' => trim($name),
                            'phone' => trim($phone),
                        ];
                    }
                }
                fclose($handle);
            }
        }

        return $contacts;
    }
    public function campaignReport($id)
    {
        $product = Product::findOrFail($id);

        $emailLogs = ProductEmailLog::where('product_id', $id)
            ->orderBy('sent_at', 'desc')
            ->paginate(20);

        $leads = ProductLead::where('product_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('products.campaign_report', compact('product', 'emailLogs', 'leads'));
    }
}
