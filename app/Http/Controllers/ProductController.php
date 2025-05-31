<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

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
                foreach ($columns as $index => $column) {
                    $searchValue = $request->input("columns.$index.search.value");
                    if (!empty($searchValue)) {
                        $query->whereRaw("$column LIKE ?", ["%{$searchValue}%"]);
                    }
                }

                $globalSearch = $request->input('search.value');
                if (!empty($globalSearch)) {
                    $query->where(function ($q) use ($columns, $globalSearch) {
                        foreach ($columns as $col) {
                            $q->orWhereRaw("$col LIKE ?", ["%{$globalSearch}%"]);
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
}
