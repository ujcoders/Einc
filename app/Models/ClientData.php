<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientData extends Model
{
    protected $fillable = [
        'Active_Inactive', 'CLIENT_ID',"CLIENT_NAME", 'SEX', 'BIRTH_DATE', 'MARITAL_STATUS',
        'NATIONALITY', 'RESIDENTIAL_STATUS', 'CATEGORY', 'MOBILE_NO', 'EMAIL_ID',
        'CITY', 'STATE', 'COUNTRY', 'PIN_CODE', 'RESI_ADDRESS', 'OCCUPATION',
        'OCCUPATION_field', 'ANNUAL_INCOME', 'PAN_NO', 'PASSPORT_NO',
        'PASSPORT_EXPIRY_DATE', 'PASSPORT_ISSUED_PLACE', 'DRIVING_LICENSE',
        'DRIVING_LICENSE_ISSUED_DATE', 'DRIVING_LICENSE_ISSUED_PLACE', 'Net_Worth',
        'Net_Worth_Date', 'PORTFOLIO_MKT_VALUE', 'OTHER_BROKER', 'Fatca_Country',
        'Fatca_Declaration', 'Fatca_Date', 'NRI_TYPE', 'NRI_PSI_NO', 'KRA_STA',
        'SMS_ID', 'GrossAnnualIncomeDate', 'ANNIVERSARY_DATE', 'Aadhar_Issue_Date',
        'AadharCard', 'brk'
    ];

    protected $table = 'client_data'; // or your actual table name


}
