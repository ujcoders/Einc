<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->string('category')->nullable();
            $table->string('status')->default('draft'); // draft, active, archived
            $table->date('launch_date')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('vendor_link')->nullable();
            $table->integer('stock')->default(0);
            $table->unsignedBigInteger('total_emails_sent')->default(0);
            $table->unsignedBigInteger('total_leads')->default(0);
            $table->timestamps();
        });

        Schema::create('product_leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('source')->nullable(); // campaign, website etc
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('product_email_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('recipient_email');
            $table->string('subject');
            $table->text('body')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_email_logs');
        Schema::dropIfExists('product_leads');
        Schema::dropIfExists('products');
    }
}
