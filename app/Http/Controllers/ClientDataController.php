<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ClientData;

class ClientDataController extends Controller
{
    public function index()
    {
        $totalClients = ClientData::count();

        $totalBrk = ClientData::distinct('brk')->count('brk');

        $totalActive = ClientData::where('Active_Inactive', 'active')->count();

        $totalInactive = ClientData::where('Active_Inactive', 'inactive')->count();

        $clientsPerCity = ClientData::select('CITY', DB::raw('COUNT(*) as total'))
            ->groupBy('CITY')
            ->orderByDesc('total')
            ->get();

        $clientsPerState = ClientData::select('STATE', DB::raw('COUNT(*) as total'))
            ->groupBy('STATE')
            ->orderByDesc('total')
            ->get();

        $clientsByStatus = ClientData::select('Active_Inactive', DB::raw('COUNT(*) as total'))
            ->groupBy('Active_Inactive')
            ->get();

        $clientsByBrk = ClientData::select('brk', DB::raw('COUNT(*) as total'))
            ->groupBy('brk')
            ->orderByDesc('total')
            ->get();

        $activeClientsByBrk = ClientData::select('brk', DB::raw('COUNT(*) as total'))
            ->where('Active_Inactive', 'active')
            ->groupBy('brk')
            ->orderByDesc('total')
            ->get();

        $inactiveClientsByBrk = ClientData::select('brk', DB::raw('COUNT(*) as total'))
            ->where('Active_Inactive', 'inactive')
            ->groupBy('brk')
            ->orderByDesc('total')
            ->get();

        $clientsByMonth = ClientData::select(
                DB::raw("FORMAT(CREATED_AT, 'yyyy-MM') as month"),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy(DB::raw("FORMAT(CREATED_AT, 'yyyy-MM')"))
            ->orderBy(DB::raw("FORMAT(CREATED_AT, 'yyyy-MM')"))
            ->get();

        $allBrks = ClientData::select('brk')->distinct()->pluck('brk')->toArray();

        $activeByBrk = $activeClientsByBrk->keyBy('brk');
        $inactiveByBrk = $inactiveClientsByBrk->keyBy('brk');

        $clientsByBrkMerged = [];
        foreach ($allBrks as $brk) {
            $clientsByBrkMerged[] = [
                'brk' => $brk,
                'active' => $activeByBrk[$brk]->total ?? 0,
                'inactive' => $inactiveByBrk[$brk]->total ?? 0,
            ];
        }

        return view('dashboard', compact(
            'totalClients',
            'totalBrk',
            'totalActive',
            'totalInactive',
            'clientsPerCity',
            'clientsPerState',
            'clientsByStatus',
            'clientsByBrk',
            'activeClientsByBrk',
            'inactiveClientsByBrk',
            'clientsByMonth',
            'clientsByBrkMerged'
        ));
    }
}
