<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return response()->json(Client::all());
    }

    public function import(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        $file = $request->file('file');
        $data = array_map('str_getcsv', file($file));

        foreach ($data as $index => $row) {
            if ($index == 0) continue; // Skip header
            Client::updateOrCreate(
                ['CLIENT_ID' => $row[0]],
                [
                    'client_name'   => $row[1] ?? '',
                    'EMAIL_ID'      => $row[2] ?? '',
                    'MOBILE_NO'     => $row[3] ?? '',
                    'RESI_ADDRESS'  => $row[4] ?? '',
                    'Max_Brokerage' => $row[5] ?? 0,
                    'brk' => $row[6] ?? '',
                ]
            );
        }

        return response()->json(['message' => 'Clients imported successfully.']);
    }
}
