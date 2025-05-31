<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientData;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class FileUploadController extends Controller
{
    public function show()
    {
        return view('file-upload');
    }

    public function upload(Request $request)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        set_time_limit(0);

        $request->validate([
            'upload_file' => 'required|file|mimes:csv,xlsx|max:512000000000',
        ]);

        $file = $request->file('upload_file');
        $filename = 'client_data_' . now()->format('Ymd_His') . '.' . $file->getClientOriginalExtension();
        $destinationPath = public_path('uploads');
        $file->move($destinationPath, $filename);

        $fullPath = $destinationPath . '/' . $filename;

        $data = Excel::toArray([], $fullPath)[0]; // <--- this is the key fix

        $headers = array_map('strtoupper', array_map('trim', $data[0]));
        unset($data[0]);

        foreach ($data as $row) {
            $clientData = array_combine($headers, $row);

            ClientData::create([
                'Active_Inactive' => $clientData['ACTIVE_INACTIVE'] ?? null,
                'CLIENT_ID' => $clientData['CLIENT_ID'] ?? null,
                'SEX' => $clientData['SEX'] ?? null,
                'BIRTH_DATE' => $this->convertExcelDate($clientData['BIRTH_DATE'] ?? null),
                'MARITAL_STATUS' => $clientData['MARITAL_STATUS'] ?? null,
                'NATIONALITY' => $clientData['NATIONALITY'] ?? null,
                'RESIDENTIAL_STATUS' => $clientData['RESIDENTIAL_STATUS'] ?? null,
                'CATEGORY' => $clientData['CATEGORY'] ?? null,
                'MOBILE_NO' => $clientData['MOBILE_NO'] ?? null,
                'EMAIL_ID' => $clientData['EMAIL_ID'] ?? null,
                'CITY' => $clientData['CITY'] ?? null,
                'STATE' => $clientData['STATE'] ?? null,
                'COUNTRY' => $clientData['COUNTRY'] ?? null,
                'PIN_CODE' => $clientData['PIN_CODE'] ?? null,
                'RESI_ADDRESS' => $clientData['RESI_ADDRESS'] ?? null,
                'OCCUPATION' => $clientData['OCCUPATION'] ?? null,
                'OCCUPATION_field' => $clientData['OCCUPATION_FIELD'] ?? null,
                'ANNUAL_INCOME' => $clientData['ANNUAL_INCOME'] ?? null,
                'PAN_NO' => $clientData['PAN_NO'] ?? null,
                'PASSPORT_NO' => $clientData['PASSPORT_NO'] ?? null,
                'PASSPORT_EXPIRY_DATE' => $this->convertExcelDate($clientData['PASSPORT_EXPIRY_DATE'] ?? null),
                'PASSPORT_ISSUED_PLACE' => $clientData['PASSPORT_ISSUED_PLACE'] ?? null,
                'DRIVING_LICENSE' => $clientData['DRIVING_LICENSE'] ?? null,
                'DRIVING_LICENSE_ISSUED_DATE' => $this->convertExcelDate($clientData['DRIVING_LICENSE_ISSUED_DATE'] ?? null),
                'DRIVING_LICENSE_ISSUED_PLACE' => $clientData['DRIVING_LICENSE_ISSUED_PLACE'] ?? null,
                'Net_Worth' => $clientData['NET_WORTH'] ?? null,
                'Net_Worth_Date' => $this->convertExcelDate($clientData['NET_WORTH_DATE'] ?? null),
                'PORTFOLIO_MKT_VALUE' => $clientData['PORTFOLIO_MKT_VALUE'] ?? null,
                'OTHER_BROKER' => $clientData['OTHER_BROKER'] ?? null,
                'Fatca_Country' => $clientData['FATCA_COUNTRY'] ?? null,
                'Fatca_Declaration' => $clientData['FATCA_DECLARATION'] ?? null,
                'Fatca_Date' => $this->convertExcelDate($clientData['FATCA_DATE'] ?? null),
                'NRI_TYPE' => $clientData['NRI_TYPE'] ?? null,
                'NRI_PSI_NO' => $clientData['NRI_PSI_NO'] ?? null,
                'KRA_STA' => $clientData['KRA_STA'] ?? null,
                'SMS_ID' => $clientData['SMS_ID'] ?? null,
                'GrossAnnualIncomeDate' => $this->convertExcelDate($clientData['GROSSANNUALINCOMEDATE'] ?? null),
                'ANNIVERSARY_DATE' => $this->convertExcelDate($clientData['ANNIVERSARY_DATE'] ?? null),
                'Aadhar_Issue_Date' => $this->convertExcelDate($clientData['AADHAR_ISSUE_DATE'] ?? null),
                'AadharCard' => $clientData['AADHARCARD'] ?? null,
                'brk' => $request['brk'] ?? $clientData['BRK'] ?? null,
            ]);
        }

        return back()->with('success', 'File uploaded and data imported successfully!');
    }

    private function convertExcelDate($value)
    {
        if (!$value) return null;

        if (is_numeric($value)) {
            try {
                return ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        if (strtotime($value)) {
            return date('Y-m-d', strtotime($value));
        }

        return null;
    }
}
