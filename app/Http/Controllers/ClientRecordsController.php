<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\ClientData;
use Yajra\DataTables\DataTables;

class ClientRecordsController extends Controller
{
    public function index()
    {
        $columns = Schema::getColumnListing((new ClientData)->getTable());

        // Remove unwanted columns like RESI_ADDRESS if needed
        $columns = array_filter($columns, fn($col) => $col !== 'RESI_ADDRESS');

        return view('clientrecords.index', compact('columns'));
    }

    public function data(Request $request)
    {
        $columns = Schema::getColumnListing((new ClientData)->getTable());
        $columns = array_filter($columns, fn($col) => $col !== 'RESI_ADDRESS');

        $query = ClientData::select($columns);

        return DataTables::of($query)
            ->filter(function ($query) use ($request, $columns) {
                foreach ($columns as $index => $column) {
                    $searchValue = $request->input("columns.$index.search.value");
                    if (!empty($searchValue)) {
                        $query->whereRaw("$column LIKE ?", ["%{$searchValue}%"]);
                    }
                }

                // Global search
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
}
