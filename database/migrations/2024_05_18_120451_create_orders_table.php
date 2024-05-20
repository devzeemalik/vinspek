<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('vin_number')->nullable();
            $table->string('year')->nullable();
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->string('dealership_name')->nullable();
            $table->string('stock_number')->nullable();
            $table->string('seller_contact')->nullable();
            $table->string('address_permanent')->nullable();
            $table->string('date')->nullable();
            $table->string('note')->nullable();
            $table->string('history_report')->nullable();
            $table->string('assessment_report')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
