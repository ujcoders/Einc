<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientData;

class ClientDataController extends Controller
{
    public function index()
    {
        $totalClients = ClientData::count();
        $totalBrk = ClientData::distinct('brk')->count('brk');
        $totalActive = ClientData::where('Active_Inactive', 'active')->count();
        $totalInactive = ClientData::where('Active_Inactive', 'inactive')->count();

        $clientsPerCity = ClientData::selectRaw('CITY, COUNT(*) as total')
            ->groupBy('CITY')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $clientsPerState = ClientData::selectRaw('STATE, COUNT(*) as total')
            ->groupBy('STATE')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $clientsByStatus = ClientData::selectRaw('Active_Inactive, COUNT(*) as total')
            ->groupBy('Active_Inactive')
            ->get();

        $clientsByBrk = ClientData::selectRaw('brk, COUNT(*) as total')
            ->groupBy('brk')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Active clients per BRK
        $activeClientsByBrk = ClientData::selectRaw('brk, COUNT(*) as total')
            ->where('Active_Inactive', 'active')
            ->groupBy('brk')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Inactive clients per BRK
        $inactiveClientsByBrk = ClientData::selectRaw('brk, COUNT(*) as total')
            ->where('Active_Inactive', 'inactive')
            ->groupBy('brk')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

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
            'inactiveClientsByBrk'
        ));
    }
}
