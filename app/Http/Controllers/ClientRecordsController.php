<?php

namespace App\Http\Controllers;

use DB;
use Log;
use App\Models\ClientData;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Schema;

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

        $filteredQuery = $query->clone();

        $dataTable = DataTables::of($filteredQuery)
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
            });

        // 🔍 Log the final SQL
        // DB::listen(function ($query) {
        //     Log::info('SQL: ' . $query->sql);
        //     \Log::info('Bindings: ', $query->bindings);
        // });

        return $dataTable->make(true);
    }
}
