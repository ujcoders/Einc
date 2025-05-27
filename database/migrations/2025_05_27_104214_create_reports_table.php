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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('to_email');
            $table->string('user_name')->nullable();
            $table->string('server_name')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('host')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->boolean('bounced')->default(false);
            $table->boolean('delivered')->default(false);
            $table->string('status')->nullable(); // sent, failed
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
