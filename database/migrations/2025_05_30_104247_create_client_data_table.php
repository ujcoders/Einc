<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('client_data', function (Blueprint $table) {
            $table->id(); // auto-increment ID
            $table->string('Active_Inactive')->nullable();
            $table->string('CLIENT_ID')->nullable();
            $table->string('CLIENT_NAME')->nullable();
            $table->string('SEX')->nullable();
            $table->date('BIRTH_DATE')->nullable();
            $table->string('MARITAL_STATUS')->nullable();
            $table->string('NATIONALITY')->nullable();
            $table->string('RESIDENTIAL_STATUS')->nullable();
            $table->string('CATEGORY')->nullable();
            $table->string('MOBILE_NO')->nullable();
            $table->string('EMAIL_ID')->nullable();
            $table->string('CITY')->nullable();
            $table->string('STATE')->nullable();
            $table->string('COUNTRY')->nullable();
            $table->string('PIN_CODE')->nullable();
            $table->text('RESI_ADDRESS')->nullable();
            $table->string('OCCUPATION')->nullable();
            $table->string('OCCUPATION_field')->nullable();
            $table->string('ANNUAL_INCOME')->nullable();
            $table->string('PAN_NO')->nullable();
            $table->string('PASSPORT_NO')->nullable();
            $table->date('PASSPORT_EXPIRY_DATE')->nullable();
            $table->string('PASSPORT_ISSUED_PLACE')->nullable();
            $table->string('DRIVING_LICENSE')->nullable();
            $table->date('DRIVING_LICENSE_ISSUED_DATE')->nullable();
            $table->string('DRIVING_LICENSE_ISSUED_PLACE')->nullable();
            $table->string('Net_Worth')->nullable();
            $table->date('Net_Worth_Date')->nullable();
            $table->string('PORTFOLIO_MKT_VALUE')->nullable();
            $table->string('OTHER_BROKER')->nullable();
            $table->string('Fatca_Country')->nullable();
            $table->string('Fatca_Declaration')->nullable();
            $table->date('Fatca_Date')->nullable();
            $table->string('NRI_TYPE')->nullable();
            $table->string('NRI_PSI_NO')->nullable();
            $table->string('KRA_STA')->nullable();
            $table->string('SMS_ID')->nullable();
            $table->date('GrossAnnualIncomeDate')->nullable();
            $table->date('ANNIVERSARY_DATE')->nullable();
            $table->date('Aadhar_Issue_Date')->nullable();
            $table->string('AadharCard')->nullable();
            $table->string('brk')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_data');
    }
};
