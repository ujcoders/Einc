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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('CLIENT_ID')->unique();
            $table->string('client_name');
            $table->string('EMAIL_ID')->nullable();
            $table->string('MOBILE_NO')->nullable();
            $table->text('RESI_ADDRESS')->nullable();
            $table->decimal('Max_Brokerage', 10, 2)->default(0);
            $table->string('brk')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
